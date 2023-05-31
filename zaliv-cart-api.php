<?php
echo '<style>body{background:#222;color:white}</style>';
$curl = curl_init();




$selectedValue = $_POST['select'];
$ip_host = $_POST['host'];
//"https://146.19.170.138:8888";
$ip_user = $_POST['usr'];
$ip_pass = $_POST['pass'];
curl_setopt_array($curl, array(
    CURLOPT_URL => $ip_host.'/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode(["username" => $ip_user,"password" => $ip_pass,]),
        CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
    ),
));


$response = curl_exec($curl);
$response = json_decode($response, true);
$token = $response['data']['token'];
curl_close($curl);







$connect = mysqli_connect("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

if ($connect == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}

if(empty($token)){
    echo "Введите правильные данные!";
    die();
}
if ($selectedValue == "default" || $selectedValue == "writeIt") {
    $sql = "INSERT INTO Panels (IP, login, pass) VALUES ('$ip_host', '$ip_user', '$ip_pass')";

    if ($connect->query($sql) === TRUE) {
    echo "Данные успешно записаны в базу данных.";
} else {
    echo "Ошибка записи данных в базу данных: " . $connect->error;
}
}

$parsed_url = parse_url($ip_host);
$hostname = $parsed_url['host'];
$ip = gethostbyname($hostname);


$all_offers = $_POST['domains'];
$new_str = str_replace("\r\n", ',', $all_offers);
$jsn_domains = json_encode($new_str);
$jsn_domains = explode(",", $jsn_domains);
//FOREACH
foreach ($jsn_domains as $card_domain) {
    $card_domain = trim($card_domain, '"');
    $without_dotcom = preg_replace('/\.([^.]+)$/', '', $card_domain);
    $usrname = str_replace("-", "_", $without_dotcom);
    $usrname = str_replace(".", "_", $usrname);
    $usr_password = bin2hex(random_bytes(7));
    $database_password = bin2hex(random_bytes(7));
    $ftp_password = bin2hex(random_bytes(7));
    $data = [
        'aliases' => [
            [
                "name" => "www." . $card_domain,
            ]
        ],
        'domain' => $card_domain,
        'email_domain' => false,
        'ips' => [
            [
                "ip" => $ip,
            ]
        ],
        'dns_domain' => null,
        'owner' => null,
        'ssh_access' => true,
        'user' => [
            "username" => $usrname . "_usr",
            "password" => $usr_password,
            "quota" => 0,
            "limits" => [
                "template_id" => null,
                "databases" => null,
                "dns_domains" => null,
                "email_domains" => null,
                "ftp_accounts" => null,
                "sftp_accounts" => null,
                "sites" => null,
                "users" => null,
            ],
            "ssh_access" => null,
        ],
        'database' => [
            "charset" => "utf8",
            "name" => $usrname,
            "server_id" => 1,
            "user" => [
                "login" => $usrname,
                "password" => $database_password,
            ],
        ],
        'mode' => "php_fpm",
        'php_version' => 82,
        'ftp_account' => [
            "username" => $usrname,
            "password" => $ftp_password,
        ],
        'sftp_account' => null,
        'backup_plan_id' => null,

    ];

    $log = date('Y-m-d H:i:s') . ' ' . 'Инфа по домену' . print_r($data, true);
    file_put_contents('log.txt', $log . PHP_EOL, FILE_APPEND);


    $data = json_encode($data);


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $ip_host.'/api/master',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ),
    ));

    $response = curl_exec($curl);
    $response = json_decode($response, true);
    $id_offer = $response['data']['id'];

    curl_close($curl);
    echo '<br><span style="color:green"> Домен : ';

    print_r($response);

    print " закинулся)</span><br>";

    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/sites/'.$id_offer,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'PUT',
  CURLOPT_POSTFIELDS =>'{"manual_changes":false,"index_page":"index.php index.html","sub_directory":"main"}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$token,
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// ///  
echo "<pre style='color:white'>";
echo $response;
echo "</pre>";

//Telegram
    $tsend = array(
        "New Site card",
        "\nДомен:", "$card_domain",


    );
    //Send the request & save response to $resp
    //chat_id
    $chat_id = -829845802;

    //tg bot api from father bot
    $tgbotapi = "bot6157079372:AAE-tl6QVL82qlLoycSSB0jLRGyQOKBjkIc";
    // path to the picture,
    $text = implode(' ', $tsend);
    // following ones are optional, so could be set as null
    $disable_web_page_preview = null;
    $reply_to_message_id = null;
    $reply_markup = null;

    $data = array(
        'chat_id' => urlencode($chat_id),
        'text' => urldecode($text),
        'disable_web_page_preview' => urlencode($disable_web_page_preview),
        'reply_to_message_id' => urlencode($reply_to_message_id),
        'reply_markup' => urlencode($reply_markup)
    );
    $url = "https://api.telegram.org/$tgbotapi/sendMessage";
    //  open connection
    $ch2 = curl_init();
    //  set the url
    curl_setopt($ch2, CURLOPT_URL, $url);
    //  number of POST vars
    curl_setopt($ch2, CURLOPT_POST, count($data));
    //  POST data
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $data);
    //  To display result of curl
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    //  execute post
    $result = curl_exec($ch2);
    //  close connection
    curl_close($ch2);
    unset($card_domain);
}
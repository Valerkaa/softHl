<?php



echo '<style>body{background:#222;text-align:center;color:white}</style>';







$selectedValue = $_POST['select'];
$ip_host = $_POST['host'];
//"https://146.19.170.138:8888";
$ip_user = $_POST['usr'];
$ip_pass = $_POST['pass'];
$dir_domain = $_POST['dirDomain'];

$curl = curl_init();

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
    CURLOPT_POSTFIELDS =>json_encode(["username" => $ip_user,"password" => $ip_pass,]),
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



$all_offers = $_POST['domains'];
$new_str = str_replace("\r\n", ',', $all_offers);
$jsn_domains = json_encode($new_str);
$jsn_domains = explode(",", $jsn_domains);
//FOREACH
foreach ($jsn_domains as $archive_to_domain){



$archive_to_domain = trim($archive_to_domain,'"');
echo '<span style="color:pink"> Работа по домену: '.$archive_to_domain;


print "</span><br>";
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $ip_host.'/api/sites/simple',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ),
));



$response = curl_exec($curl);
$response = json_decode($response, true);




curl_close($curl);
sleep(1);




foreach ($response['data'] as $i){
    if($i['domain'] == $dir_domain){
        $id_offer = $i['id'];
        $path_offer = $i['index_dir'];
        $name_folder = $i['domain'];
        $main_path = trim($path_offer,$name_folder);
    }
}
$data = array(
  'domain' => $archive_to_domain,
  'site_id' => $id_offer,
  'sub_directory' => $archive_to_domain
);

$payload = json_encode($data, true);

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/master/subdomain',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'PUT',
  CURLOPT_POSTFIELDS => $payload,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$token,
  ),
));

$response = curl_exec($curl);
$response = json_decode($response,true);
$id_card = $response['data']['id'];
if($id_card){
echo "<h1 style='color:green'> Поддомен создался ";
print_r($archive_to_domain);
echo "</h1>";
}else{
    echo "<h1 style='color:red'> Поддомен не создан "; 
    print_r($response);
    echo "</h1>";

}

curl_close($curl);




  $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $ip_host.'/api/certificates',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"type":"letsencrypt","email":"work228qwe@gmail.com","virtualhost":'.$id_card.',"length":2048}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
print_r($response);

//Telegram
    $tsend = array(
        "New Poddomen card",
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

}


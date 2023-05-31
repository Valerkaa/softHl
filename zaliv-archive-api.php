<?php

echo '<style>body{background:#222;text-align:center;color:white}</style>';
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
    if($i['domain'] == $archive_to_domain){
        $id_offer = $i['id'];
        $path_offer = $i['index_dir'];
        $name_folder = $i['domain'];
        $main_path = $path_offer;
    }
}

echo '<span style="color:pink"> Работа по домену: '.$archive_to_domain;

print "</span><br>";





$curl = curl_init();
$data_path = [
 'files' => [
     [
     "path" => $path_offer."/main",
     ]
 ]
];




curl_setopt_array($curl, array(
    CURLOPT_URL => $ip_host.'/api/files/batch?site='.$id_offer,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data_path),
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ),
));

$response = curl_exec($curl);
curl_close($curl);
sleep(2);


$data = [
 'folder' => [
     "name" => "main",
     "path" => $main_path,
 ]

];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $ip_host.'/api/folder/new?site='.$id_offer,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ),
));

$response = curl_exec($curl);
$response = json_decode($response, true);
curl_close($curl);








      $target_file = basename($_FILES["fileToUpload"]["name"]);

      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
       echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
       $fileName = basename( $_FILES["fileToUpload"]["name"]);
       $fileString = strval($fileName);
      } else{
         echo "Sorry, there was an error uploading your file.";
      }
    

$filepath = $fileString;
$curlFile = new CURLFile(realpath($filepath));


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/files/upload?file='.$filepath.'&site='.$id_offer.'&path='.$path_offer."/main",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array( 'file' => $curlFile),
  CURLOPT_HTTPHEADER => array(
    'Accept: application/zip',
    'Authorization: Bearer '.$token,
  ),
));

$response = curl_exec($curl);

curl_close($curl);










echo '<br><span style="color:green"> Доставлен : ';

print_r($filepath);

print "</span><br>";



$archive_path = $path_offer."/main/".$filepath;


sleep(3);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/archive/extract?site='.$id_offer,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
 CURLOPT_POSTFIELDS =>'{
    "extractarchive": {
        "path": "'.$archive_path.'",
        "target": "'.$path_offer.'"
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$token,
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo '<br><span style="color:green"> Архив распечатался : PS:Code 300';


print "</span><br>";


$data = [
    'files' =>[
        "0" =>[
        "path" => $main_path."/main/".$filepath,
        ]
        ]
    ];

sleep(4);
$curl = curl_init();


curl_setopt_array($curl, array(
    CURLOPT_URL => $ip_host.'/api/files/batch?site='.$id_offer,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ),
));

$response = curl_exec($curl);
curl_close($curl);
echo '<br><span style="color:red"> Архив удалился : Все гуд (ЦАК-ЦАК-ЦАК)';


print "</span><br>";










//Telegram
$tsend = array(
    "New Registration",
    "\nайди:", "$id_offer",
    "\nДомен:", "$archive_to_domain",



            );
    //Send the request & save response to $resp
    //chat_id
    $chat_id = -829845802;

    //tg bot api from father bot
    $tgbotapi ="bot6157079372:AAE-tl6QVL82qlLoycSSB0jLRGyQOKBjkIc";
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
    unset($archive_to_domain);
}
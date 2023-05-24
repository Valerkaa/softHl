<?php

echo '<style>body{background:#222;text-align:center;color:white}</style>';
$curl = curl_init();
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
    CURLOPT_URL => $ip_host.'/api/sites/list?filter[type]=all&filter[order]=date&filter[offset]=0&filter[limit]=20&filter[query]='.$archive_to_domain,
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
$id_offer = $response['data'][0]['id'];

$path_offer = $response['data'][0]['index_dir'];
$name_folder = $response['data'][0]['domain'];
$main_path = trim($path_offer,$name_folder);



curl_close($curl);
sleep(1);



$curl = curl_init();
$data_path = [
 'files' => [
     [
     "path" => $path_offer,
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
     "name" => $name_folder,
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





$filepath = "archive.zip";



$curlFile = new CURLFile(realpath($filepath));

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/files/upload?file='.$filepath.'&site='.$id_offer.'&path='.$path_offer,
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



$archive_path = $path_offer."/archive.zip";


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
        "path" => $main_path.$archive_to_domain."/archive.zip",
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
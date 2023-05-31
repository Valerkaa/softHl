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






//connect main host
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://5.101.180.160:8888/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode(["username" => "fastuser","password" => "N4DF9dw8bw6TwdK6",]),
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
    ),
));


$response = curl_exec($curl);
$response = json_decode($response, true);
$tokenHell = $response['data']['token'];
curl_close($curl);
//close connect



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
    if($i['domain'] == $archive_to_domain){
        $id_offer = $i['id'];
        $path_offer = $i['index_dir'];
        $name_folder = $i['domain'];
        $poddomen = strstr($archive_to_domain, '.', true);
        $main_path = $path_offer."/".$poddomen;
    }
}
echo '<span style="color:pink">';
echo "<br> Поддмен ".$poddomen;
echo "<br> Меин пас ".$main_path;
print "</span><br>";


$curl = curl_init();
$data_path = [
 'files' => [
     [
     "path" => $main_path,
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
echo '<span style="color:yellow">';
echo "<br> Ответ удаления ".$response;

print "</span><br>";
$data = [
 'folder' => [
     "name" => $poddomen,
     "path" => $path_offer,
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
echo '<span style="color:blue">';
echo "<br> Ответ cоздания ";
print_r($response);

print "</span><br>";


  $target_file = basename($_FILES["fileToUploads"]["name"]);

      if (move_uploaded_file($_FILES["fileToUploads"]["tmp_name"], $target_file)) {
       echo "The file ". basename( $_FILES["fileToUploads"]["name"]). " has been uploaded.";
       $fileName = basename( $_FILES["fileToUploads"]["name"]);
       $fileString = strval($fileName);
      } else{
         echo "Sorry, there was an error uploading your file.";
      }
    

$filepath = $fileString;
$curlFile = new CURLFile(realpath($filepath));


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $ip_host.'/api/files/upload?file='.$filepath.'&site='.$id_offer.'&path='.$main_path,
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







$archive_path = $main_path."/".$filepath;


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
        "target": "'.$main_path.'"
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$token,
  ),
));

$response = curl_exec($curl);

curl_close($curl);


$data = [
    'files' =>[
        "0" =>[
        "path" => $main_path."/".$filepath,
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





}



// 
// 

$curl = curl_init();


curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://5.101.180.160:8888/api/files/batch?site=14',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{"files":[{"path":"/var/www/hell_leads_w_usr/data/www/hell-leads.win/soft/'.$filepath.'"}]}',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$tokenHell,
    ),
));

$response = curl_exec($curl);
curl_close($curl);

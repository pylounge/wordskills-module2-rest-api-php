<?php
/*
$data = [
    "first_name" => "Maxim",
    "last_name" => "XX",
    "phone" => "89101224566",
    "document_number" => "7567999222",
    "password" => ""
];

$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
$curl = curl_init('http://localhost/rest-api-ll/public/api/register');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
// Принимаем в виде массива. (false - в виде объекта)
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Content-Length: ' . strlen($data_string))
);
$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);
*/

/*
$data = [
    "phone" => "89001238833",
    "password" => "password",
];

$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
$curl = curl_init('http://localhost/rest-api-ll/public/api/login');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
// Принимаем в виде массива. (false - в виде объекта)
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Content-Length: ' . strlen($data_string))
);
$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);

*/

/*
$data = [
    "phone" => "89001238833",
    "password" => "password",
];

$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
$curl = curl_init('http://localhost/rest-api-ll/public/api/user');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
//curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
// Принимаем в виде массива. (false - в виде объекта)
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer 71a188119a445974f921a80b17d717f22')
 );


$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);
*/


$params = array('query' => 'zAn');

$curl = curl_init('http://localhost/rest-api-ll/public/api/airport?' . http_build_query($params));
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json')
 );


$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);

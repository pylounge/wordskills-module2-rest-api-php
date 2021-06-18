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

/*
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
*/

/*

$params = array('from' => 'SVO',
                'to' => 'KZN',
                'date1' => '2020-10-01',
                'date2' => '2020-10-13',
                'passengers' => '2');

$curl = curl_init('http://localhost/rest-api-ll/public/api/flight?' . http_build_query($params));
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json')
 );


$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);
*/



$data = [
    'flight_from' => ['id' => '1', 'date' => '2020-09-20'],
    'flight_back' => ['id' => '2', 'date' => '2020-09-30'],
    'passengers' => [
        [
            "first_name" => "Maxim",
            "last_name" => "Melnikov",
            "birth_date" => "1998-07-18",
            "document_number" => "7567999222"
        ],
        [
            "first_name" => "Ivan",
            "last_name" => "Melnikov",
            "birth_date" => "2000-08-19",
            "document_number" => "7567999333"
        ],
    ]
];

$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
$curl = curl_init('http://localhost/rest-api-ll/public/api/booking');
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


/*
$code = "TESTA";
$curl = curl_init('http://localhost/rest-api-ll/public/api/booking/' . $code);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json')
 );


$result = curl_exec($curl);
curl_close($curl);
echo '<pre>';
echo ($result);
*/

<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');


$host = "localhost";
$user = "root";
$password = "";
$db_name = "gallery";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error){
    http_response_code(400);
    echo "connection error :(";
}

?>
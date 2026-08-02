<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "Laboranova";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => "Database Connection Failed"]);
    die("Database connection error ..." . mysqli_connect_error());


}



?>
<?php

header("Content-type: application/json");

require __DIR__ . "/vendor/autoload.php";

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

$website = $_GET["website"];

$sql = "SELECT * FROM a WHERE website=?";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $website);
$stmt->execute();
while ($row = $result->fetch_assoc()) {
    print_r($row);
}

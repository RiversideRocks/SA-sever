<?php

header("Content-type: application/json");

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$d = $_GET["d"];

if($d == "*")
{
    $a = array("success" => "false", "message" => "Invalid origin");
    die(json_encode($a, true));
}

if($d == "")
{
    $a = array("success" => "false", "message" => "Invalid origin");
    die(json_encode($a, true));
}

if($d == "null")
{
    $a = array("success" => "false", "message" => "Invalid origin");
    die(json_encode($a, true));
}

header("Access-Control-Allow-Origin: ${d}");

$servername = $_ENV['MYSQL_SERVER'];
$username = $_ENV["MYSQL_USERNAME"];
$password = $_ENV["MYSQL_PASSWORD"];
$dbname = $_ENV["MYSQL_DATABASE"];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

/*

epoch: Time in seconds since Unix epoch
website: Originating website
country: Country of client making the request
token: SHA-256 hash of client's IP address. Used for unique visitor metrics

*/

$epoch = htmlspecialchars($_POST["epoch"]);
$country = htmlspecialchars($_POST["country"]);
$website = htmlspecialchars($d);
$token = htmlspecialchars($_POST["token"]);


$stmt = $conn->prepare("INSERT INTO a (epoch, website, country, token) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $epoch, $website, $country, $token);
$stmt->execute();
$stmt->close();

$a = array("success" => "true", "message" => "OK");
die(json_encode($a, true));

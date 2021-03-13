<?php

header("Content-type: application/json");

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
//$stmt->bind_param("s", $website);
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $website);
$stmt->execute();
//$stmt->close();
$result = $stmt->get_result();
$hits = array();
while ($row = $result->fetch_assoc()) {
    array_push($hits, $row["country"]);
}

//$final = array("hits" => count($hits));
//echo json_encode($final);



$sql = "SELECT country, count(*) as hits from a GROUP BY country ORDER BY hits DESC";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$countries = array();
$hits = array();
while ($row = $result->fetch_assoc()) {
    $country = $row["country"];
    if(! $country)
    {
        $country = "Unknown";
    }
    $hit = $row["hits"];
    array_push($countries, $country);
    array_push($hits, $hit);
}


echo json_encode(array(
    "total_hits" => count($hits),
    "verbose" =>
        array(
            "countries" => $countries,
            "hits" => $hits,
        )
));

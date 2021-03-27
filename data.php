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
$hits = 0;
while ($row = $result->fetch_assoc()) {
    $hits = $hits + 1;
}

//$final = array("hits" => count($hits));
//echo json_encode($final);



$sql = "SELECT country, count(*) as hits from a WHERE website=? GROUP BY country ORDER BY hits DESC";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $website);
$stmt->execute();
$result = $stmt->get_result();
$countries = array();
$hitz = array();
while ($row = $result->fetch_assoc()) {
    $country = $row["country"];
    if(! $country)
    {
        $country = "Unknown";
    }
    $hit = $row["hits"];
    array_push($countries, $country);
    array_push($hitz, $hit);
}

$sql = "SELECT * FROM a WHERE website=?";
//$stmt->bind_param("s", $website);
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $website);
$stmt->execute();
$result = $stmt->get_result();
$u = array();
while ($row = $result->fetch_assoc()) {
    if(in_array($row["token"], $u))
    {
        // Move on
    }else{
        array_push($u, $row["token"]);
    }
}

$unique = count($u);

$seconds = $hits * 5;
$minutes = round($seconds/60);
$hours = round($minutes/60, 1);
$days = round($hours/24, 1);

echo json_encode(array(
    "total_hits" => $hits,
    "unique_hits" => $unique,
    "time_spent" =>
        array(
            "seconds" => $seconds,
            "minutes" => $minutes,
            "hours" => $hours,
            "days" => $days
         ),
    "verbose" =>
        array(
            "countries" => $countries,
            "hits" => $hitz,
        )
));

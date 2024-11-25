<?php
$servername = "localhost"; // or your server name
$username = "root";
$password = "";
$dbname = "fundfinitygrants";
$port = "3308";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>

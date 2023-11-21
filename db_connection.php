<?php
$host = "localhost"; // Replace with your host name
$username = "taanes2380"; // Replace with your MySQL username
$password = "ACEace2380"; // Replace with your MySQL password
$database = "tech_heaven"; // Replace with your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


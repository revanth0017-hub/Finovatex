<?php
$host = "localhost";
$username = "root"; // use your DB username
$password = "";     // use your DB password
$database = "finovatex";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

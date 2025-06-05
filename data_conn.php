<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "data";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Connection successful, you can use $conn in your scripts

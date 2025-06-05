<?php
$sname = "localhost";
$uname = "root";      // fixed typo from 'unmae' to 'uname'
$password = "";
$db_name = "users";

// Create connection
$conn = mysqli_connect($sname, $uname, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// else
// {
//     echo "Connection successful";
// }

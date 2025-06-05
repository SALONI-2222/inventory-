<?php
session_start();
include "db_conn.php";

// Validate function to sanitize input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(isset($_POST['username']) && isset($_POST['password'])) {
    $uname = validate($_POST['username']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: index.php?error=Username is required");
        exit();
    }

    if (empty($pass)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    // Prepare SQL statements to prevent SQL Injection (using prepared statements)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE Name=? AND Password=?");
    $stmt->bind_param("ss", $uname, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt1 = $conn->prepare("SELECT * FROM salesman WHERE Name=? AND Password=?");
    $stmt1->bind_param("ss", $uname, $pass);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // No need to re-check username and password here since prepared statement already checked it
        $_SESSION["Name"] = $row['Name'];
        $_SESSION['name'] = $row['Name'];   // Fixed 'name' vs 'Name' typo
        $_SESSION['S_No'] = $row['S_No'];
        header("Location: home.php");
        exit();
    } 
    else if ($result1->num_rows === 1) {
        $row = $result1->fetch_assoc();
        $_SESSION["Name"] = $row['Name'];
        $_SESSION['name'] = $row['Name'];  // Fixed 'name' vs 'Name' typo
        $_SESSION['S_No'] = $row['S_No'];
        header("Location: home.php");
        exit();
    } 
    else {
        header("Location: index.php?error=Incorrect Username or Password");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

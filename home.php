<?php
session_start();

if (isset($_SESSION['S_No']) && isset($_SESSION['Name'])) {
    header("location: wel.php");
    exit();
} 

if (!isset($_SESSION['S_No']) || !isset($_SESSION['Name'])) {
    // Not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// If logged in, you can display home page content here or include it.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['Name']); ?>!</h1>
    <p>This is the home page.</p>
    <a href="logout.php">Logout</a>
</body>
</html>

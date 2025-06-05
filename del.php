<?php
include "welcome.php";
include "data_conn.php";

$sq = "SELECT * FROM bill";
$res = mysqli_query($conn, $sq);

if (mysqli_num_rows($res) > 0) {
    // Delete all rows in one query
    $delete = mysqli_query($conn, "DELETE FROM `bill`");

    if ($delete) {
        echo "Data in Bill deleted successfully.";
    } else {
        echo "Error deleting data: " . mysqli_error($conn);
    }
} else {
    // No rows found, redirect or show message
    header("Location: welcome.php");
    exit;
}
?>

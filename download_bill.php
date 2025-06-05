<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bill_id = isset($_GET['bill_id']) ? intval($_GET['bill_id']) : 0;
if ($bill_id <= 0) {
    die("Invalid bill id.");
}

$bill = $conn->query("SELECT * FROM bill WHERE bill_id = $bill_id")->fetch_assoc();
if (!$bill) {
    die("Bill not found.");
}

$sales = $conn->query("
    SELECT s.product_id, s.quantity, s.total_price, p.name 
    FROM sales s 
    JOIN product_data p ON s.product_id = p.id
    WHERE s.bill_id = $bill_id
");

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=bill_$bill_id.txt");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

echo "========== BILL ==========\n";
echo "Bill ID: $bill_id\n";
echo "Customer Name: " . $bill['customer_name'] . "\n";
echo "Bill Date: " . $bill['bill_date'] . "\n";
echo "Membership: " . $bill['membership'] . "\n\n";

echo "----- Products -----\n";

while ($row = $sales->fetch_assoc()) {
    echo "Product ID: " . $row['product_id'] . "\n";
    echo "Product Name: " . $row['name'] . "\n";
    echo "Quantity: " . $row['quantity'] . "\n";
    echo "Total Price: ₹" . $row['total_price'] . "\n";
    echo "--------------------\n";
}

echo "\nTotal Bill Amount: ₹" . $bill['total_amount'] . "\n";
echo "=========================\n";
?>

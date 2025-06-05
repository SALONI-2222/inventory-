<?php
include "data_conn.php";
include "welcome.php";

$message = "";
$error = "";

// Step 1: Detect user choice (via POST or GET)
$action = $_POST['action'] ?? $_GET['action'] ?? null;

// Step 2: Handle add product submission
if (isset($_POST['add_submit'])) {
    $id = $_POST['add_id'];

    // Check if product exists
    $checkStmt = $conn->prepare("SELECT id FROM product_data WHERE id = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $error = "Product ID $id already exists. Cannot add duplicate.";
    } else {
        $stmt = $conn->prepare("INSERT INTO product_data (id, name, price, units, expiry_date, rack_no) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param(
                "isidis",
                $_POST['add_id'],
                $_POST['add_name'],
                $_POST['add_price'],
                $_POST['add_units'],
                $_POST['add_expirydate'],
                $_POST['add_rack']
            );
            if ($stmt->execute()) {
                $message = "Product added successfully!";
            } else {
                $error = "Error inserting product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare statement: " . $conn->error;
        }
    }
    $checkStmt->close();
    $action = 'add'; // stay on add form
}

// Step 3: Handle update product submission
if (isset($_POST['update_submit'])) {
    $update_id = $_POST['update_id'];

    // Check if product exists
    $checkStmt = $conn->prepare("SELECT * FROM product_data WHERE id = ?");
    $checkStmt->bind_param("i", $update_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Product ID $update_id does not exist.";
    } else {
        $row = $result->fetch_assoc();
        $new_units = isset($_POST['update_units']) ? $_POST['update_units'] : $row['units'];

        $updateStmt = $conn->prepare("UPDATE product_data SET units = ? WHERE id = ?");
        $updateStmt->bind_param("ii", $new_units, $update_id);

        if ($updateStmt->execute()) {
            $message = "Product ID $update_id updated successfully (units updated).";
        } else {
            $error = "Error updating product: " . $updateStmt->error;
        }
        $updateStmt->close();
    }
    $checkStmt->close();
    $action = 'update'; // stay on update form
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Add / Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f7f9fc;
        }
        h1 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        
        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }
        input[type="number"],
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px 0;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
            color: green;
        }
        .error {
            color: #d93025;
            text-align: center;
            font-weight: 700;
            margin-top: 20px;
        }
        .choice-form {
            max-width: 320px;
            margin: 60px auto;
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .choice-form label {
            font-weight: normal;
            margin: 10px 0;
            font-size: 1.2rem;
        }
        .choice-form button {
            background-color: #28a745;
            font-weight: 600;
            margin-top: 15px;
        }
        .choice-form button:hover {
            background-color: #1e7e34;
        }
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php if (!$action): ?>
    <!-- Step 1: Ask for action -->
    <div class="choice-form">
        <h1>Inventory Management</h1>
        <form method="get" action="">
            <label>
                <input type="radio" name="action" value="add" required> Add New Product
            </label>
            <label>
                <input type="radio" name="action" value="update"> Update Existing Product Units
            </label>
            <button type="submit">Continue</button>
        </form>
    </div>

<?php elseif ($action === 'add'): ?>
    <div class="container">
        <h1>Add New Product</h1>
        <form method="post" novalidate>
            <label for="add_id">Product ID</label>
            <input type="number" id="add_id" name="add_id" required />

            <label for="add_name">Product Name</label>
            <input type="text" id="add_name" name="add_name" required />

            <label for="add_price">Product Price</label>
            <input type="number" id="add_price" name="add_price" step="0.01" min="0" required />

            <label for="add_units">Number of Units</label>
            <input type="number" id="add_units" name="add_units" min="0" required />

            <label for="add_expirydate">Expiry Date</label>
            <input type="date" id="add_expirydate" name="add_expirydate" required />

            <label for="add_rack">Rack Number</label>
            <input type="number" id="add_rack" name="add_rack" min="0" required />

            <button type="submit" name="add_submit">Add Product</button>
        </form>

        <div class="back-link"><a href="?">← Back to Choice</a></div>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

<?php elseif ($action === 'update'): ?>
    <div class="container">
        <h1>Update Existing Product Units</h1>
        <form method="post" novalidate>
            <label for="update_id">Product ID</label>
            <input type="number" id="update_id" name="update_id" required />

            <label for="update_units">New Units</label>
            <input type="number" id="update_units" name="update_units" min="0" required />

            <button type="submit" name="update_submit">Update Product</button>
        </form>

        <div class="back-link"><a href="?">← Back to Choice</a></div>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

<?php endif; ?>

</body>
</html>

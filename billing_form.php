<?php
include "data_conn.php";
include "welcome.php";

$message = "";
$error = "";

if (isset($_POST['submit'])) {
    $customer_name = trim(mysqli_real_escape_string($conn, $_POST['customer_name'] ?? ''));
    $membership = (isset($_POST['membership']) && $_POST['membership'] === 'Yes') ? 'Yes' : 'No';
    $num_products = intval($_POST['num_products'] ?? 0);

    if ($num_products <= 0) {
        $error = "Please enter a valid number of products.";
    } elseif (empty($customer_name)) {
        $error = "Please enter Customer Name.";
    } else {
        $products = $_POST['products'] ?? [];
        // Validate products array: each with product_id and quantity
        if (count($products) !== $num_products) {
            $error = "Product details count mismatch.";
        } else {
            // Validate each product and accumulate total
            $total_bill = 0;
            $stock_updates = [];
            $sales_data = [];

            foreach ($products as $index => $prod) {
                $prod_id = intval($prod['product_id'] ?? 0);
                $qty = intval($prod['quantity'] ?? 0);

                if ($prod_id <= 0 || $qty <= 0) {
                    $error = "Invalid Product ID or Quantity at product #" . ($index + 1);
                    break;
                }

                // Check product availability
                $stmt = $conn->prepare("SELECT name, price, units FROM product_data WHERE id = ?");
                $stmt->bind_param("i", $prod_id);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows === 0) {
                    $error = "Product ID $prod_id not found (product #" . ($index + 1) . ")";
                    $stmt->close();
                    break;
                }

                $row = $res->fetch_assoc();
                $stmt->close();

                if ($qty > $row['units']) {
                    $error = "Insufficient units for product '{$row['name']}' (only {$row['units']} left)";
                    break;
                }

                $line_total = $row['price'] * $qty;
                $total_bill += $line_total;

                // Prepare data for stock update and sales insert
                $stock_updates[] = ['id' => $prod_id, 'new_units' => $row['units'] - $qty];
                $sales_data[] = [
                    'product_id' => $prod_id,
                    'quantity' => $qty,
                    'total_price' => $line_total,
                    'sale_date' => date('Y-m-d H:i:s'),
                ];
            }

            if (!$error) {
                // Insert into bill table
                $bill_date = date('Y-m-d H:i:s');
                $stmt_bill = $conn->prepare("INSERT INTO bill (bill_date, customer_name, total_amount, membership) VALUES (?, ?, ?, ?)");
                $stmt_bill->bind_param("ssds", $bill_date, $customer_name, $total_bill, $membership);

                if ($stmt_bill->execute()) {
                    $bill_id = $conn->insert_id;

                    // Update stocks
                    foreach ($stock_updates as $su) {
                        $stmt_upd = $conn->prepare("UPDATE product_data SET units = ? WHERE id = ?");
                        $stmt_upd->bind_param("ii", $su['new_units'], $su['id']);
                        $stmt_upd->execute();
                        $stmt_upd->close();
                    }

                    // Insert sales entries
                    $stmt_sales = $conn->prepare("INSERT INTO sales (product_id, quantity, sale_date, total_price, bill_id) VALUES (?, ?, ?, ?, ?)");
                    foreach ($sales_data as $sd) {
                        $stmt_sales->bind_param("iisdi", $sd['product_id'], $sd['quantity'], $sd['sale_date'], $sd['total_price'], $bill_id);
                        $stmt_sales->execute();
                    }
                    $stmt_sales->close();

                    $message = "Bill created successfully! Bill ID: $bill_id";
                } else {
                    $error = "Failed to insert bill: " . $conn->error;
                }

                $stmt_bill->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Billing Form</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script>
function showProductForm() {
    const num = parseInt(document.getElementById('num_products').value);
    const container = document.getElementById('products_container');
    container.innerHTML = ''; // Clear old inputs

    if (num > 0) {
        for (let i = 0; i < num; i++) {
            const div = document.createElement('div');
            div.classList.add('mb-3', 'border', 'p-3', 'rounded');
            div.innerHTML = `
                <h5>Product #${i+1}</h5>
                <label>Product ID:</label>
                <input type="number" name="products[${i}][product_id]" min="1" required class="form-control mb-2"/>
                <label>Quantity:</label>
                <input type="number" name="products[${i}][quantity]" min="1" required class="form-control"/>
            `;
            container.appendChild(div);
        }
        document.getElementById('submit_btn').style.display = 'block';
    } else {
        document.getElementById('submit_btn').style.display = 'none';
    }
}
</script>
</head>
<body>
<div class="container mt-5" style="max-width: 700px;">
    <h1 class="mb-4 text-center">Billing Form</h1>

    <?php if ($message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="mb-3">
            <label for="num_products" class="form-label">Number of Products</label>
            <input type="number" name="num_products" id="num_products" min="1" required
                   class="form-control"
                   value="<?php echo isset($_POST['num_products']) ? (int)$_POST['num_products'] : ''; ?>"
                   oninput="showProductForm()" />
        </div>

        <div id="products_container">
            <?php
            // Re-populate product inputs on error postback
            if (isset($_POST['products']) && is_array($_POST['products'])) {
                $prods = $_POST['products'];
                foreach ($prods as $i => $prod) {
                    $pid = intval($prod['product_id'] ?? 0);
                    $qty = intval($prod['quantity'] ?? 0);
                    echo '<div class="mb-3 border p-3 rounded">';
                    echo "<h5>Product #" . ($i + 1) . "</h5>";
                    echo '<label>Product ID:</label>';
                    echo '<input type="number" name="products[' . $i . '][product_id]" min="1" required class="form-control mb-2" value="' . htmlspecialchars($pid) . '"/>';
                    echo '<label>Quantity:</label>';
                    echo '<input type="number" name="products[' . $i . '][quantity]" min="1" required class="form-control" value="' . htmlspecialchars($qty) . '"/>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name" required
                   class="form-control"
                   value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>" />
        </div>

        <div class="mb-3">
            <label for="membership" class="form-label">Membership</label>
            <select name="membership" id="membership" required class="form-select">
                <option value="No" <?php if (isset($_POST['membership']) && $_POST['membership'] === 'No') echo 'selected'; ?>>No</option>
                <option value="Yes" <?php if (isset($_POST['membership']) && $_POST['membership'] === 'Yes') echo 'selected'; ?>>Yes</option>
            </select>
        </div>

        <button type="submit" name="submit" id="submit_btn" class="btn btn-primary w-100"
                style="<?php echo (isset($_POST['num_products']) && intval($_POST['num_products']) > 0) ? '' : 'display:none;'; ?>">
            Submit
        </button>
    </form>
</div>

<script>
// If page reload and num_products exists, show product fields automatically
window.onload = function() {
    if (document.getElementById('num_products').value > 0) {
        showProductForm();
    }
}
</script>
</body>
</html>

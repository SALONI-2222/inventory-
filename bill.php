<?php

include "welcome.php";
$conn = new mysqli("localhost", "root", "", "data");

function escape($val) {
    return htmlspecialchars($val);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Billing System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(120deg, #f0f4ff, #d9e4ff);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
    }
    .navbar {
      margin-top: 10px;
      background-color: #223a5e;
    }
    .navbar-brand, .nav-link {
      color: white !important;
      font-weight: bold;
    }
    .container {
      padding-top: 40px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .no-bill {
      text-align: center;
      padding: 60px;
      background: white;
      border-radius: 12px;
      margin-top: 60px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-5">
      <a class="navbar-brand" href="#">Sales & Billing System</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="billing_form.php">Create Bill</a></li>
          <li class="nav-item"><a class="nav-link" href="bill.php">All Bills</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
<?php
if (!isset($_GET['bill_id'])) {
    // Show list of all bills
    $result = $conn->query("SELECT bill_id, customer_name, bill_date, total_amount, membership FROM bill ORDER BY bill_date DESC");

    if ($result->num_rows > 0): ?>
      <h3 class="mb-4">All Generated Bills</h3>
      <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card p-3">
              <h5>Bill #<?php echo escape($row['bill_id']); ?></h5>
              <p><b>Name:</b> <?php echo escape($row['customer_name']); ?></p>
              <p><b>Date:</b> <?php echo escape($row['bill_date']); ?></p>
              <p><b>Membership:</b> <?php echo escape($row['membership']); ?></p>
              <p><b>Total:</b> ₹<?php echo number_format($row['total_amount'], 2); ?></p>
              <a href="bill.php?bill_id=<?php echo $row['bill_id']; ?>" class="btn btn-sm btn-primary mt-2">View Bill</a>
              <a href="download_bill.php?bill_id=<?php echo $row['bill_id']; ?>" target="_blank" class="btn btn-sm btn-success mt-2">Download</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="no-bill">
        <h4>No Bills Found</h4>
        <p>Create your first bill to get started!</p>
        <a href="billing_form.php" class="btn btn-primary mt-3">Create New Bill</a>
      </div>
    <?php endif;

} else {
    // Show detailed bill by bill_id
    $bill_id = intval($_GET['bill_id']);

    $stmt = $conn->prepare("SELECT * FROM bill WHERE bill_id = ?");
    $stmt->bind_param("i", $bill_id);
    $stmt->execute();
    $bill_result = $stmt->get_result();
    $bill = $bill_result->fetch_assoc();
    $stmt->close();

    if (!$bill): ?>
        <div class="no-bill">
          <h4>Bill not found!</h4>
          <p>The requested Bill ID doesn't exist. Please select a valid bill.</p>
          <a href="bill.php" class="btn btn-warning mt-3">Back to Bill List</a>
        </div>
    <?php else:
        // Fetch sales lines for this bill
        $stmt2 = $conn->prepare("SELECT s.*, p.name FROM sales s 
                                 JOIN product_data p ON s.product_id = p.id 
                                 WHERE s.bill_id = ?");
        $stmt2->bind_param("i", $bill_id);
        $stmt2->execute();
        $sale_result = $stmt2->get_result();
        $sale_lines = [];
        while ($row = $sale_result->fetch_assoc()) {
            $sale_lines[] = $row;
        }
        $stmt2->close();

        // Calculate subtotal, discount, and final total
        $subtotal = 0;
        foreach ($sale_lines as $line) {
            $subtotal += $line['total_price'];
        }
        $discount = (strtolower($bill['membership']) === 'yes') ? $subtotal * 0.1 : 0;
        $final_total = $subtotal - $discount;
        ?>

        <div class="card p-4">
          <h3 class="text-center">Bill #<?php echo escape($bill_id); ?></h3>
          <p><b>Customer Name:</b> <?php echo escape($bill['customer_name']); ?></p>
          <p><b>Bill Date:</b> <?php echo escape($bill['bill_date']); ?></p>
          <p><b>Membership:</b> <?php echo escape($bill['membership']); ?></p>

          <?php if (!empty($sale_lines)): ?>
          <table class="table mt-4">
            <thead>
              <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Qty</th>
                <th>Total Price (₹)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sale_lines as $line): ?>
                <tr>
                  <td><?php echo escape($line['product_id']); ?></td>
                  <td><?php echo escape($line['name']); ?></td>
                  <td><?php echo escape($line['quantity']); ?></td>
                  <td><?php echo number_format($line['total_price'], 2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="d-flex justify-content-end">
            <div class="me-5">
              <p><b>Subtotal:</b> ₹<?php echo number_format($subtotal, 2); ?></p>
              <p><b>Discount:</b> ₹<?php echo number_format($discount, 2); ?></p>
              <p><b>Total Bill:</b> ₹<?php echo number_format($final_total, 2); ?></p>
            </div>
          </div>
          <div class="text-center mt-3">
            <a href="download_bill.php?bill_id=<?php echo $bill_id; ?>" class="btn btn-success">Download PDF</a>
            <button onclick="window.print();" class="btn btn-dark ms-3">Print</button>
            <a href="del.php" class="btn btn-danger ms-3">Clear Bill</a>
          </div>
          <?php else: ?>
            <p>No products found for this bill.</p>
          <?php endif; ?>
        </div>

    <?php endif;
}
?>
  </div>

</body>
</html>

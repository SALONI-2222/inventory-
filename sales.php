<?php
session_start();
include "data_conn.php";
include "welcome.php";

// Prepare and execute the query using mysqli prepared statement (good practice)
$sql = "
    SELECT s.product_id, p.name, s.quantity, s.total_price
    FROM sales s
    JOIN product_data p ON s.product_id = p.id
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sales Status</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />
  
  <style>
    body {
      background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 40px 20px;
      margin: 0;
    }

    h1 {
      color: #2c3e50;
      font-weight: 700;
      margin-bottom: 40px;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.1);
      user-select: none;
    }

    table {
      width: 90%;
      max-width: 900px;
      border-collapse: separate !important;
      border-spacing: 0 12px;
      box-shadow: 0 8px 20px rgba(44, 62, 80, 0.15);
      border-radius: 12px;
      overflow: hidden;
      background-color: #ffffffcc;
      transition: box-shadow 0.3s ease;
    }

    table:hover {
      box-shadow: 0 12px 30px rgba(44, 62, 80, 0.3);
    }

    thead tr {
      background: #2c3e50;
      color: white;
      font-size: 1.1rem;
      letter-spacing: 0.05em;
      user-select: none;
    }

    th, td {
      padding: 18px 24px;
      text-align: center;
      font-size: 1.1rem;
      vertical-align: middle;
    }

    tbody tr {
      background: #f9f9f9;
      border-radius: 10px;
      transition: background-color 0.3s ease, transform 0.2s ease;
      cursor: default;
    }

    tbody tr:hover {
      background-color: #d6e4ff;
      transform: scale(1.02);
      box-shadow: 0 4px 15px rgba(44, 62, 80, 0.15);
    }

    tbody tr:not(:last-child) {
      margin-bottom: 12px;
    }

    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tbody tr {
        margin-bottom: 20px;
        background: #f0f4ff;
        border-radius: 10px;
        padding: 12px;
      }
      tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        font-size: 1rem;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 20px;
        width: 45%;
        padding-left: 15px;
        font-weight: 600;
        text-align: left;
        color: #34495e;
      }
    }

    /* Add margin-bottom for the navigation button */
    .btn-back {
      margin-top: 30px;
      background-color: #2c3e50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
    }
    .btn-back:hover {
      background-color: #1a2733;
      color: #fff;
    }
  </style>
</head>
<body>

  <h1>Sales Status</h1>

  <?php if ($res && $res->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Name</th>
          <th>Quantity</th>
          <th>Total Price (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php while($r = $res->fetch_assoc()): ?>
          <tr>
            <td data-label="Product ID"><?= htmlspecialchars($r['product_id']) ?></td>
            <td data-label="Name"><?= htmlspecialchars($r['name']) ?></td>
            <td data-label="Quantity"><?= htmlspecialchars($r['quantity']) ?></td>
            <td data-label="Total Price">₹<?= number_format($r['total_price'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="color: #444; font-size: 1.2rem; margin-top: 30px;">No sales records found.</p>
  <?php endif; ?>

  <a href="billing_form.php" class="btn-back">Back to Billing</a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>

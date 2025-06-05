<?php
session_start();
include "data_conn.php";
include "welcome.php";

$sq = "SELECT * FROM product_data";
$res = mysqli_query($conn, $sq);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Stock Status</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />

  <style>
    body {
      background: linear-gradient(135deg, #d0e6f7 0%, #a8c0ff 100%);
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 40px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      color: #223a5e;
      font-weight: 700;
      margin-bottom: 40px;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.1);
      user-select: none;
    }

    table {
      width: 95%;
      max-width: 1100px;
      border-collapse: separate !important;
      border-spacing: 0 14px;
      background-color: #ffffffcc;
      border-radius: 14px;
      box-shadow: 0 9px 22px rgba(34, 58, 94, 0.12);
      transition: box-shadow 0.3s ease;
      overflow: hidden;
    }

    table:hover {
      box-shadow: 0 14px 35px rgba(34, 58, 94, 0.3);
    }

    thead tr {
      background-color: #223a5e;
      color: #ffffff;
      font-weight: 600;
      font-size: 1.15rem;
      letter-spacing: 0.04em;
      user-select: none;
    }

    th, td {
      padding: 18px 25px;
      text-align: center;
      font-size: 1.1rem;
      vertical-align: middle;
    }

    tbody tr {
      background: #f8fbff;
      border-radius: 12px;
      transition: background-color 0.3s ease, transform 0.25s ease;
      cursor: default;
    }

    tbody tr:hover {
      background-color: #d2e2ff;
      transform: scale(1.03);
      box-shadow: 0 6px 20px rgba(34, 58, 94, 0.15);
    }

    /* Responsive for smaller screens */
    @media (max-width: 900px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tbody tr {
        margin-bottom: 24px;
        background: #e6efff;
        border-radius: 14px;
        padding: 18px 16px;
      }
      tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        font-size: 1rem;
        border: none;
        border-bottom: 1px solid #ccc;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 18px;
        width: 45%;
        padding-left: 15px;
        font-weight: 600;
        text-align: left;
        color: #223a5e;
      }
      tbody td:last-child {
        border-bottom: none;
      }
    }

  </style>
</head>
<body>

  <h1>Stock Status</h1>

  <?php if (mysqli_num_rows($res) > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Product Id</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        
        <th>Expiry Date</th>
        <th>Rack No.</th>
      </tr>
    </thead>
    <tbody>
      <?php while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td data-label="Product Id"><?php echo htmlspecialchars($r['id']); ?></td>
        <td data-label="Name"><?php echo htmlspecialchars($r['name']); ?></td>
        <td data-label="Price"><?php echo htmlspecialchars($r['price']); ?></td>
        <td data-label="Quantity"><?php echo htmlspecialchars($r['units']); ?></td>
        
        <td data-label="Expiry Date"><?php echo htmlspecialchars($r['expiry_date']); ?></td>
        <td data-label="Rack No."><?php echo htmlspecialchars($r['rack_no']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p style="color: #223a5e; font-size: 1.25rem; margin-top: 30px; user-select:none;">
      No stock data available.
    </p>
  <?php endif; ?>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>

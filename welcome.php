<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        /* Navbar background gradient with subtle shadow */
        .navbar {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            box-shadow: 0 4px 8px rgba(78, 84, 200, 0.3);
            transition: background 0.4s ease;
        }

        .navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: #fff !important;
            letter-spacing: 1.5px;
            transition: color 0.3s ease;
        }

        .navbar .navbar-brand:hover {
            color: #ffd700 !important;
            text-shadow: 0 0 8px #ffd700;
        }

        /* Nav links styling */
        .navbar-nav .nav-link {
            color: #ddd !important;
            font-weight: 600;
            position: relative;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
        }

        /* Hover underline animation */
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #ffd700;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 4px;
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 60%;
        }

        .navbar-nav .nav-link:hover {
            color: #ffd700 !important;
            text-shadow: 0 0 6px #ffd700;
        }

        /* Active link highlight */
        .navbar-nav .nav-link.active {
            color: #ffd700 !important;
            font-weight: 700;
        }

        /* Logout link special styling */
        .navbar-nav .nav-link.logout {
            color: #ff4b5c !important;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link.logout:hover {
            color: #ff1f3a !important;
            text-shadow: 0 0 8px #ff1f3a;
        }

        /* Navbar-toggler animation */
        .navbar-toggler {
            border-color: #fff;
            transition: border-color 0.3s ease;
        }
        .navbar-toggler:hover {
            border-color: #ffd700;
        }
        .navbar-toggler-icon {
            filter: invert(1);
            transition: filter 0.3s ease;
        }
        .navbar-toggler:hover .navbar-toggler-icon {
            filter: invert(0.7) sepia(1) saturate(5) hue-rotate(30deg);
        }

        /* Responsive spacing */
        @media (max-width: 992px) {
            .navbar-nav {
                background: #4e54c8;
                padding: 1rem;
                border-radius: 0 0 10px 10px;
                box-shadow: 0 4px 12px rgba(78, 84, 200, 0.5);
            }

            .navbar-nav .nav-link {
                padding: 1rem 0;
                font-size: 1.2rem;
                color: #fff !important;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="stock.php">Inventory Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="stock.php">Stock</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="update.php">Update</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sales.php">Sales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="billing_form.php">Billing Form</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="bill.php">Bill</a>
        </li>
        <li class="nav-item">
          <a class="nav-link logout" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>

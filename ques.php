<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ASK</title>
<style>
  body {
    background-color: #f2f6ff;
    margin-top: 60px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 90vh;
  }

  form#url {
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    width: 320px;
    text-align: center;
    animation: fadeInScale 0.6s ease forwards;
  }

  label {
    font-size: 1.2rem;
    font-weight: 600;
    display: block;
    margin-bottom: 15px;
    color: #333;
  }

  input.data {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    outline: none;
  }

  input.data:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 8px rgba(74, 144, 226, 0.4);
  }

  input.Submit {
    margin-top: 20px;
    width: 100%;
    padding: 12px 0;
    background-color: #4a90e2;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s ease;
    letter-spacing: 1px;
  }

  input.Submit:hover {
    background-color: #357ABD;
  }

  .error-msg {
    margin-top: 20px;
    color: #d93025;
    font-weight: 600;
    animation: shake 0.3s ease;
  }

  @keyframes fadeInScale {
    0% {
      opacity: 0;
      transform: scale(0.9);
    }
    100% {
      opacity: 1;
      transform: scale(1);
    }
  }

  @keyframes shake {
    0%, 100% { transform: translateX(0);}
    20%, 60% { transform: translateX(-8px);}
    40%, 80% { transform: translateX(8px);}
  }
</style>
</head>
<body>

<form id="url" action="ques.php" method="get" autocomplete="off" >
  <label for="ques"><b>Do you want to Continue?</b></label>
  <input class="data" type="text" id="ques" name="ques" required placeholder="Type Yes or No" pattern="^(Yes|No)$" title="Please enter 'Yes' or 'No'">
  <input class="Submit" type="submit" name="submit" value="Submit" >
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["submit"])) {
    $val = trim($_GET["ques"]);
    if ($val === "No") {
      header("Location: bill.php");
      exit();
    } elseif ($val === "Yes") {
      header("Location: billing_form.php");
      exit();
    } else {
      echo '<div class="error-msg">Invalid value entered. Please enter "Yes" or "No".</div>';
    }
  }
}
?>

</body>
</html>

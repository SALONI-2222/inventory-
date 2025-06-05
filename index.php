<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post" autocomplete="off">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <label for="username">Username</label><br />
        <input id="username" name="username" type="text" placeholder="Username" required /><br />

        <label for="password">Password</label><br />
        <input id="password" name="password" type="password" placeholder="Password" required /><br />

        <button type="submit">Login</button>
    </form>
</body>
</html>

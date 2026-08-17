<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['user_role'] === 'Admin' ? 'index.php' : 'shop.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/styles.css">
    <script type="text/javascript" src="resources/jquery.min.js"></script>
    <title>Log In - StoreBooks</title>
</head>
<body>
    <header>
        <h1>StoreBooks</h1>
    </header>

    <main>
        <section id="login-section">
            <h2>Log In</h2>
            <p id="login-message" class="form-message"></p>
            <form id="loginForm" method="POST" action="db/auth_request.php">
                <input type="email" name="user_email" id="login-email" placeholder="Email" required>
                <input type="password" name="user_password" id="login-password" placeholder="Password" required>
                <button type="submit" name="login_user">Log In</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </section>
    </main>
    <script type="text/javascript" src="resources/auth.js"></script>
</body>
</html>
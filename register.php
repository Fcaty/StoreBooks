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
    <title>Register - StoreBooks</title>
</head>
<body>
    <header>
        <h1>StoreBooks</h1>
    </header>

    <main>
        <section id="register-section">
            <h2>Register</h2>
            <p id="register-message" class="form-message"></p>
            <form id="registerForm" method="POST" action="db/auth_request.php">
                <input type="text" name="user_name" id="register-name" placeholder="Full Name" required>
                <input type="email" name="user_email" id="register-email" placeholder="Email" required>
                <input type="password" name="user_password" id="register-password" placeholder="Password (min. 8 characters)" required>
                <input type="password" name="confirm_password" id="register-confirm-password" placeholder="Confirm Password" required>
                <button type="submit" name="register_user">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Log in here</a>.</p>
        </section>
    </main>
    <script type="text/javascript" src="resources/auth.js"></script>
</body>
</html>
<?php
session_start();
require_once 'db.php';
$database = new Database();

if (isset($_POST['register_user'])) {
    $name = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $password = $_POST['user_password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
        exit;
    }

    if ($password !== $confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    $database->select('users', 'user_id', ['user_email' => $email]);
    if ($database->res->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'An account with that email already exists.']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $database->insert('users', [
        'user_name' => $name,
        'user_role' => 'Customer',
        'user_email' => $email,
        'user_password' => $hashed
    ]);

    echo json_encode(['success' => true, 'message' => 'Account created! You can now log in.']);
    exit;
}

if (isset($_POST['login_user'])) {
    $email = trim($_POST['user_email']);
    $password = $_POST['user_password'];

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter your email and password.']);
        exit;
    }

    $database->select('users', '*', ['user_email' => $email]);
    $user = $database->res->fetch_assoc();

    if (!$user || !password_verify($password, $user['user_password'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect email or password.']);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['user_name'];
    $_SESSION['user_role'] = $user['user_role'];

    $redirect = ($user['user_role'] === 'Admin') ? 'index.php' : 'shop.php';

    echo json_encode(['success' => true, 'redirect' => $redirect]);
    exit;
}
?>
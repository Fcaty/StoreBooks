<?php
session_start();
require_once 'db.php';
require_once 'auth.php';

$database = new Database();
$auth = new Auth($database);

//registers a new user account
if (isset($_POST['register_user'])) {
    $result = $auth->register(
        $_POST['user_name'],
        $_POST['user_email'],
        $_POST['user_password'],
        $_POST['confirm_password']
    );

    echo json_encode($result);
    exit();
}

//logs a user in and starts their session
if (isset($_POST['login_user'])) {
    $result = $auth->login($_POST['user_email'], $_POST['user_password']);
    echo json_encode($result);
    exit();
}

?>
<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("location: login.php");
        exit;
    }

    if (isset($_SESSION['user_id'])) {
        header('Location: ' . ($_SESSION['user_role'] === 'Admin' ? 'admin.php' : 'shop.php'));
        exit;
    }
?>
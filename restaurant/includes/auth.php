<?php
session_start();

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /restaurant/index.php');
        exit();
    }
}

function login($userId, $role) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['role'] = $role;
}

function logout() {
    session_destroy();
    header('Location: /restaurant/index.php');
    exit();
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (empty($_SESSION['admin'])) {
        header('Location: login.php');
        exit();
    }
}

function is_logged_in() {
    return !empty($_SESSION['admin']);
}
?>

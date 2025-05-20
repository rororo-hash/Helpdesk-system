<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan admin sudah login, jika tidak redirect ke login
function require_login() {
    if (empty($_SESSION['admin'])) {
        header('Location: login.php'); // ubah path jika perlu
        exit();
    }
}

// Semak sama ada admin sudah login
function is_logged_in() {
    return !empty($_SESSION['admin']);
}

// Fungsi login: jika berjaya set session admin = true
function login($username, $password) {
    // Gantikan dengan credential sebenar atau dari environment
    $ADMIN_USER = getenv('ADMIN_USER') ?: 'admin';
    $ADMIN_PASS = getenv('ADMIN_PASS') ?: 'password123';

    if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
        $_SESSION['admin'] = true;
        return true;
    }
    return false;
}

// Fungsi logout: kosongkan session dan redirect
function logout() {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

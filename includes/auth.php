<?php
// Mulakan sesi jika belum bermula
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Paksa pengguna login.
 * Jika belum login, akan redirect ke login.php
 */
function require_login() {
    if (empty($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Semak sama ada pengguna sedang login
 * @return bool
 */
function is_logged_in() {
    return !empty($_SESSION['username']);
}

/**
 * Semak jika pengguna ialah admin
 * @return bool
 */
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Semak jika pengguna ialah staff
 * @return bool
 */
function is_staff() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

/**
 * Dapatkan nama pengguna yang sedang login
 * @return string|null
 */
function current_user() {
    return $_SESSION['username'] ?? null;
}

/**
 * Dapatkan peranan pengguna (admin/staff)
 * @return string|null
 */
function current_role() {
    return $_SESSION['role'] ?? null;
}

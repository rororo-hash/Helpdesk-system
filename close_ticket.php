<?php
session_start();
require_once 'includes/auth.php';       // Untuk fungsi is_logged_in()
require_once 'includes/functions.php';  // Untuk load_tickets() dan save_tickets()

// Semak login
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Dapatkan id tiket dari URL
$id = $_GET['id'] ?? '';

// Jika tiada id, redirect semula ke halaman tiket
if ($id === '') {
    header("Location: tickets.php");
    exit();
}

// Muatkan semua tiket
$tickets = load_tickets();
$found = false;

// Cari tiket berdasarkan id dan tukar status kepada 'Selesai'
foreach ($tickets as &$ticket) {
    if ($ticket['id'] === $id) {
        $ticket['status'] = 'Selesai';
        $found = true;
        break;
    }
}

// Jika tiket ditemui, simpan perubahan
if ($found) {
    save_tickets($tickets);
}

// Redirect ke halaman tiket semula
header("Location: dashboard.php");
exit();

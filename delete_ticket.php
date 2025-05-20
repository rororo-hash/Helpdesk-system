<?php
session_start();
require_once 'includes/auth.php';  // pastikan dalam ni ada function require_login()
require_once 'includes/functions.php'; // pastikan load_tickets() dan save_tickets() ada

// Pastikan admin telah login
require_login();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = $_GET['id'];
$tickets = load_tickets();
$found = false;

foreach ($tickets as $index => $ticket) {
    if ($ticket['id'] === $id) {
        unset($tickets[$index]);
        $found = true;
        break;
    }
}

if ($found) {
    // Susun semula indeks
    $tickets = array_values($tickets);
    save_tickets($tickets);
    $_SESSION['message'] = "Tiket telah dipadam.";
} else {
    $_SESSION['message'] = "Tiket tidak dijumpai.";
}

header('Location: dashboard.php');
exit;

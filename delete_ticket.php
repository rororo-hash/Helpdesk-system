<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Pastikan admin telah login
require_login();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = $_GET['id'];
$tickets = load_tickets();
$updated = false;

// Cari dan buang tiket berdasarkan ID
foreach ($tickets as $index => $ticket) {
    if ($ticket['id'] === $id) {
        unset($tickets[$index]);
        $updated = true;
        break;
    }
}

if ($updated) {
    // Susun semula array supaya index tersusun
    $tickets = array_values($tickets);
    save_tickets($tickets);
    $_SESSION['message'] = "Tiket telah dipadam.";
} else {
    $_SESSION['message'] = "Tiket tidak dijumpai.";
}

header('Location: dashboard.php');
exit;
?>

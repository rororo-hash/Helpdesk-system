<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';

$tickets = load_tickets();
foreach ($tickets as &$ticket) {
    if ($ticket['id'] === $id) {
        $ticket['status'] = 'Selesai';  // guna status konsisten dengan dashboard
        break;
    }
}
save_tickets($tickets);

header("Location: tickets.php");
exit();

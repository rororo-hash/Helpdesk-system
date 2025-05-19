<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';
close_ticket($id);
header("Location: tickets.php");
exit();

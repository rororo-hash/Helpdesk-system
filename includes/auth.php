<?php
session_start();
$ADMIN_USERNAME = "admin";
$ADMIN_PASSWORD = "admin123";
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

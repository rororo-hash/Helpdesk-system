<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

require_login();

$id = $_GET['id'] ?? '';
$tickets = load_tickets();

$ticket = null;
foreach ($tickets as $t) {
    if ($t['id'] === $id) {
        $ticket = $t;
        break;
    }
}

if (!$ticket) {
    die("Tiket tidak dijumpai.");
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Paparan Tiket</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #f4f4f4;
            width: 30%;
        }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Maklumat Tiket</h2>
    <table>
        <tr><th>ID</th><td><?= htmlspecialchars($ticket['id']) ?></td></tr>
        <tr><th>Case ID</th><td><?= htmlspecialchars($ticket['case_id'] ?? '-') ?></td></tr>
        <tr><th>Lokasi</th><td><?= htmlspecialchars($ticket['location'] ?? '-') ?></td></tr>
        <tr><th>Status Part</th><td><?= htmlspecialchars($ticket['part_status'] ?? '-') ?></td></tr>
        <tr><th>Subjek</th><td><?= htmlspecialchars($ticket['subject']) ?></td></tr>
        <tr><th>Deskripsi</th><td><?= nl2br(htmlspecialchars($ticket['description'])) ?></td></tr>
        <tr><th>Status</th><td><?= htmlspecialchars($ticket['status']) ?></td></tr>
        <tr><th>Tarikh</th><td><?= htmlspecialchars($ticket['created_at']) ?></td></tr>
    </table>

    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>

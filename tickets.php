<?php
session_start();
require_once 'includes/functions.php';
require_login();

$file = 'data/tickets.json';
$tickets = [];
if (file_exists($file)) {
    $tickets = json_decode(file_get_contents($file), true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Senarai Tiket</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #4f46e5; color: white; }
        a { text-decoration: none; color: #4f46e5; }
    </style>
</head>
<body>
    <h2>Senarai Tiket</h2>
    <a href="new_ticket.php">+ Buat Tiket Baharu</a><br><br>
    <table>
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Subjek</th>
                <th>Lokasi</th>
                <th>Status Part</th>
                <th>Tarikh</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr><td colspan="6" style="text-align:center;">Tiada tiket ditemui.</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['case_id']) ?></td>
                        <td><?= htmlspecialchars($t['subject']) ?></td>
                        <td><?= htmlspecialchars($t['location']) ?></td>
                        <td><?= htmlspecialchars($t['part_status']) ?></td>
                        <td><?= htmlspecialchars($t['created_at']) ?></td>
                        <td><?= htmlspecialchars($t['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
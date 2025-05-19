<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$tickets = getTickets();
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard Tiket</title></head>
<body>
<h2>Dashboard Tiket</h2>
<p><a href="ticket_create.php">+ Tambah Tiket Baru</a> | <a href="logout.php">Log Keluar</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Subjek</th>
        <th>Status</th>
        <th>Tindakan</th>
    </tr>
    <?php foreach ($tickets as $ticket): ?>
    <tr>
        <td><?= htmlspecialchars($ticket['id']) ?></td>
        <td><?= htmlspecialchars($ticket['subject']) ?></td>
        <td><?= htmlspecialchars($ticket['status']) ?></td>
        <td>
            <a href="ticket_edit.php?id=<?= urlencode($ticket['id']) ?>">Edit</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

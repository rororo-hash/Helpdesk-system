<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$tickets = getTickets();

// Cari tiket
$search = $_GET['search'] ?? '';
if ($search) {
    $tickets = array_filter($tickets, function ($ticket) use ($search) {
        return stripos($ticket['case_id'], $search) !== false ||
               stripos($ticket['subject'], $search) !== false ||
               stripos($ticket['location'], $search) !== false;
    });
}

// Susun tiket ikut tarikh terbaru
usort($tickets, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Tiket</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-Baru { color: white; background-color: blue; padding: 2px 6px; border-radius: 4px; }
        .status-Selesai { color: white; background-color: green; padding: 2px 6px; border-radius: 4px; }
        .status-Dalam\ Proses { color: white; background-color: orange; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<h2>Dashboard Tiket</h2>
<p><a href="create_ticket.php">+ Tambah Tiket Baru</a></p>

<form method="get">
    <input type="text" name="search" placeholder="Cari Case ID, Subjek, Lokasi" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Cari</button>
</form>

<?php if (empty($tickets)): ?>
    <p>Tiada tiket dijumpai.</p>
<?php else: ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Case ID</th>
            <th>Lokasi</th>
            <th>Status Part</th>
            <th>Subjek</th>
            <th>Status</th>
            <th>Tarikh</th>
            <th>Tindakan</th>
        </tr>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars($ticket['id']) ?></td>
                <td><?= htmlspecialchars($ticket['case_id'] ?? '-') ?></td>
                <td><?= htmlspecialchars($ticket['location'] ?? '-') ?></td>
                <td><?= htmlspecialchars($ticket['part_status'] ?? '-') ?></td>
                <td><?= htmlspecialchars($ticket['subject']) ?></td>
                <td><span class="status-<?= str_replace(' ', '\ ', $ticket['status']) ?>">
                    <?= htmlspecialchars($ticket['status']) ?></span></td>
                <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                <td>
                    <a href="edit_ticket.php?id=<?= urlencode($ticket['id']) ?>">Edit</a> |
                    <a href="close_ticket.php?id=<?= urlencode($ticket['id']) ?>" onclick="return confirm('Tutup tiket ini?')">Tutup</a> |
                    <a href="delete_ticket.php?id=<?= urlencode($ticket['id']) ?>" onclick="return confirm('Padam tiket ini?')">Padam</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<p><a href="logout.php">Log Keluar</a></p>
</body>
</html>
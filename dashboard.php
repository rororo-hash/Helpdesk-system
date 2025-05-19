<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$tickets = load_tickets();

$search = $_GET['search'] ?? '';
if ($search) {
    $tickets = array_filter($tickets, function ($ticket) use ($search) {
        return stripos($ticket['case_id'], $search) !== false ||
               stripos($ticket['subject'], $search) !== false ||
               stripos($ticket['location'], $search) !== false;
    });
}

usort($tickets, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Tiket</title>
    <style>
        /* (Sama macam style yang anda dah guna tadi) */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #fafafa;
        }
        h2 { color: #333; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
        form { margin-bottom: 20px; }
        input[type="text"] {
            padding: 6px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 6px 12px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover { background-color: #0056b3; }
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f1f1f1; }
        .status-Baru {
            color: white;
            background-color: #007BFF;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            font-size: 0.85em;
        }
        .status-Selesai {
            color: white;
            background-color: #28a745;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            font-size: 0.85em;
        }
        .status-DalamProses {
            color: white;
            background-color: #fd7e14;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            font-size: 0.85em;
        }
        /* Responsive */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            th { display: none; }
            td {
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #eee;
            }
            td:before {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                content: attr(data-label);
                color: #333;
            }
            td:last-child { border-bottom: 2px solid #007BFF; }
        }
    </style>
</head>
<body>
    <h2>Dashboard Tiket</h2>
    <p><a href="create_ticket.php">+ Tambah Tiket Baru</a></p>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Cari Case ID, Subjek, Lokasi" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if (empty($tickets)): ?>
        <p>Tiada tiket dijumpai.</p>
    <?php else: ?>
        <table>
            <thead>
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
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                <?php $statusClass = str_replace(' ', '', $ticket['status']); ?>
                <tr>
                    <td data-label="ID"><?= htmlspecialchars($ticket['id']) ?></td>
                    <td data-label="Case ID"><?= htmlspecialchars($ticket['case_id'] ?? '-') ?></td>
                    <td data-label="Lokasi"><?= htmlspecialchars($ticket['location'] ?? '-') ?></td>
                    <td data-label="Status Part"><?= htmlspecialchars($ticket['part_status'] ?? '-') ?></td>
                    <td data-label="Subjek"><?= htmlspecialchars($ticket['subject']) ?></td>
                    <td data-label="Status">
                        <span class="status-<?= htmlspecialchars($statusClass) ?>">
                            <?= htmlspecialchars($ticket['status']) ?>
                        </span>
                    </td>
                    <td data-label="Tarikh"><?= htmlspecialchars($ticket['created_at']) ?></td>
                    <td data-label="Tindakan">
                        <a href="edit_ticket.php?id=<?= urlencode($ticket['id']) ?>">Edit</a> |
                        <a href="close_ticket.php?id=<?= urlencode($ticket['id']) ?>" onclick="return confirm('Tutup tiket ini?')">Tutup</a> |
                        <a href="delete_ticket.php?id=<?= urlencode($ticket['id']) ?>" onclick="return confirm('Padam tiket ini?')">Padam</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="logout.php">Log Keluar</a></p>
</body>
</html>

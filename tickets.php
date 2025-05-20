<?php
ob_start(); // Elak output sebelum header

require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$tickets = load_tickets();

$perPage = 5;
$totalTickets = count($tickets);
$totalPages = ceil($totalTickets / $perPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;

$start = ($page - 1) * $perPage;
$ticketsPage = array_slice($tickets, $start, $perPage);

function statusClass($status) {
    return 'status-' . str_replace(' ', '', strtolower($status));
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Tiket Helpdesk</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; background: #fafafa; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ccc; padding: 0.8em; text-align: left; }
        th { background-color: #f2f2f2; }
        tr.closed { color: gray; text-decoration: line-through; }
        tr:hover { background-color: #f9f9f9; }
        a.button {
            padding: 0.4em 0.8em;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            margin-right: 5px;
        }
        a.button.close {
            background: #dc3545;
        }
        .status-baru {
            color: white;
            background-color: #007bff;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-dalamproses {
            color: white;
            background-color: #fd7e14;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-selesai {
            color: white;
            background-color: #28a745;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
            display: inline-block;
        }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            thead tr {
                display: none;
            }
            tr {
                margin-bottom: 1.2em;
                border: 1px solid #ccc;
                padding: 1em;
                background: white;
                border-radius: 8px;
            }
            td {
                border: none;
                padding: 0.5em 0;
                position: relative;
                padding-left: 50%;
                text-align: left;
                white-space: normal;
            }
            td::before {
                position: absolute;
                top: 0.5em;
                left: 1em;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                content: attr(data-label);
                color: #555;
            }
            a.button {
                margin: 0.3em 0.3em 0.3em 0;
                display: inline-block;
                font-size: 0.85em;
                padding: 0.3em 0.6em;
            }
        }
        .pagination {
            margin-top: 1em;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 6px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a.active {
            background: #0056b3;
            font-weight: bold;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <h1>Tiket Helpdesk</h1>
    <p>
        <a href="create_ticket.php" class="button">Tambah Tiket Baru</a> |
        <a href="logout.php">Log Keluar</a>
    </p>

    <?php if (empty($ticketsPage)): ?>
        <p>Tiada tiket untuk dipaparkan.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Subjek</th>
                <th>Status</th>
                <th>Tarikh</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($ticketsPage as $ticket): 
            $statusClass = statusClass($ticket['status']);
            ?>
            <tr class="<?= $ticket['status'] === 'closed' ? 'closed' : '' ?>">
                <td data-label="ID"><?= htmlspecialchars($ticket['id']) ?></td>
                <td data-label="Subjek"><?= htmlspecialchars($ticket['subject']) ?></td>
                <td data-label="Status"><span class="<?= htmlspecialchars($statusClass) ?>"><?= htmlspecialchars($ticket['status']) ?></span></td>
                <td data-label="Tarikh"><?= htmlspecialchars($ticket['created_at']) ?></td>
                <td data-label="Tindakan">
                    <a href="edit_ticket.php?id=<?= urlencode($ticket['id']) ?>" class="button">Edit</a>
                    <?php if ($ticket['status'] !== 'closed'): ?>
                    <a href="close_ticket.php?id=<?= urlencode($ticket['id']) ?>" class="button close" onclick="return confirm('Adakah anda pasti mahu tutup tiket ini?')">Tutup</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="?page=<?= $p ?>" class="<?= ($p == $page) ? 'active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php ob_end_flush(); ?>
</body>
</html>

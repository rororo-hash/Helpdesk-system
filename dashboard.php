<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

require_login();

$tickets = load_tickets();

function get_unique_months($tickets) {
    $months = [];
    foreach ($tickets as $ticket) {
        $month = substr($ticket['created_at'], 0, 7);
        if (!in_array($month, $months)) {
            $months[] = $month;
        }
    }
    rsort($months);
    return $months;
}

$all_months = get_unique_months($tickets);
$filter_month = $_GET['month'] ?? '';
$search = trim($_GET['search'] ?? '');

$tickets = array_filter($tickets, function ($ticket) use ($search, $filter_month) {
    $matchSearch = true;
    if ($search !== '') {
        $matchSearch = stripos($ticket['case_id'], $search) !== false ||
                       stripos($ticket['subject'], $search) !== false ||
                       stripos($ticket['location'], $search) !== false;
    }
    $matchMonth = true;
    if ($filter_month !== '') {
        $ticket_month = substr($ticket['created_at'], 0, 7);
        $matchMonth = ($ticket_month === $filter_month);
    }
    return $matchSearch && $matchMonth;
});

$tickets_baru = [];
$tickets_dalam_proses = [];
$tickets_selesai = [];

foreach ($tickets as $ticket) {
    $status = strtolower(str_replace(' ', '', trim($ticket['status'])));
    if ($status === 'selesai') {
        $tickets_selesai[] = $ticket;
    } elseif ($status === 'dalamproses') {
        $tickets_dalam_proses[] = $ticket;
    } else {
        $tickets_baru[] = $ticket;
    }
}

function sort_tickets_by_month_desc(&$tickets) {
    usort($tickets, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
}

sort_tickets_by_month_desc($tickets_baru);
sort_tickets_by_month_desc($tickets_dalam_proses);
sort_tickets_by_month_desc($tickets_selesai);

// Pagination setup
$page_baru = max(1, intval($_GET['page_baru'] ?? 1));
$page_proses = max(1, intval($_GET['page_proses'] ?? 1));
$page_selesai = max(1, intval($_GET['page_selesai'] ?? 1));
$per_page = 5;

function paginate($tickets, $page, $per_page) {
    $offset = ($page - 1) * $per_page;
    return array_slice($tickets, $offset, $per_page);
}

function render_pagination($base_url, $current_page, $total_items, $per_page, $page_param) {
    $total_pages = ceil($total_items / $per_page);
    if ($total_pages <= 1) return;

    echo '<div style="margin-top:10px;">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $url = $base_url . "&$page_param=$i";
        $style = $i === $current_page
            ? 'font-weight:bold;text-decoration:underline;background:#007BFF;color:#fff;padding:2px 6px;border-radius:4px;'
            : 'color:#007BFF;';
        echo "<a href=\"$url\" style=\"margin-right:8px;$style\">Page $i</a>";
    }
    echo '</div>';
}

$tickets_baru_page = paginate($tickets_baru, $page_baru, $per_page);
$tickets_proses_page = paginate($tickets_dalam_proses, $page_proses, $per_page);
$tickets_selesai_page = paginate($tickets_selesai, $page_selesai, $per_page);

$total_baru = count($tickets_baru);
$total_proses = count($tickets_dalam_proses);
$total_selesai = count($tickets_selesai);

function render_table($tickets_group, $start_index = 1) {
    if (empty($tickets_group)) {
        echo "<p>Tiada tiket dijumpai.</p>";
        return;
    }

    echo '<table>
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
            <tbody>';

    $i = $start_index;
    foreach ($tickets_group as $ticket) {
        $statusClass = 'status-' . strtolower(str_replace(' ', '', $ticket['status']));
        echo '<tr>';
        echo '<td data-label="ID">Case-' . $i . '</td>';
        echo '<td data-label="Case ID">' . htmlspecialchars($ticket['case_id'] ?? '-') . '</td>';
        echo '<td data-label="Lokasi">' . htmlspecialchars($ticket['location'] ?? '-') . '</td>';
        echo '<td data-label="Status Part">' . htmlspecialchars($ticket['part_status'] ?? '-') . '</td>';
        echo '<td data-label="Subjek">' . htmlspecialchars($ticket['subject']) . '</td>';
        echo '<td data-label="Status"><span class="' . htmlspecialchars($statusClass) . '">' . htmlspecialchars($ticket['status']) . '</span></td>';
        echo '<td data-label="Tarikh">' . htmlspecialchars($ticket['created_at']) . '</td>';
        echo '<td data-label="Tindakan">
                <a href="view_ticket.php?id=' . urlencode($ticket['id']) . '">Papar</a> |
                <a href="edit_ticket.php?id=' . urlencode($ticket['id']) . '">Edit</a> |
                <a href="close_ticket.php?id=' . urlencode($ticket['id']) . '" onclick="return confirm(\'Tutup tiket ini?\')">Tutup</a> |
                <a href="delete_ticket.php?id=' . urlencode($ticket['id']) . '" onclick="return confirm(\'Padam tiket ini?\')">Padam</a>
              </td>';
        echo '</tr>';
        $i++;
    }

    echo '</tbody></table>';
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Tiket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #fafafa;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        h2 { color: #333; margin: 0; }
        .user-info {
            font-weight: bold;
            color: #555;
        }
        .logout-btn {
            margin-left: 15px;
            font-weight: normal;
            color: #007BFF;
            text-decoration: none;
            border: 1px solid #007BFF;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .logout-btn:hover {
            background-color: #007BFF;
            color: white;
        }

        a.tambah-tiket {
            float: right;
            background-color: #28a745;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }
        a.tambah-tiket:hover {
            background-color: #218838;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        input[type="text"], select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        button {
            padding: 6px 14px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 1em;
        }
        button:hover { background-color: #0056b3; }

        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f1f1f1; }

        .status-baru { background-color: #007BFF; }
        .status-selesai { background-color: #28a745; }
        .status-dalamproses { background-color: #fd7e14; }
        [class^="status-"] {
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <header>
        <h2>Welcome | Dashboard </h2>
        <div class="user-info">
            Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Tetamu') ?></strong>
            <a href="logout.php" class="logout-btn">Log Keluar</a>
        </div>
    </header>

    <a href="create_ticket.php" class="tambah-tiket">+ Tambah Tiket Baru</a>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Cari Case ID, Subjek, Lokasi" value="<?= htmlspecialchars($search) ?>">
        <select name="month" onchange="this.form.submit()">
            <option value="">-- Semua Bulan --</option>
            <?php foreach ($all_months as $month): 
                $display = date('F Y', strtotime($month . '-01'));
                $selected = ($filter_month === $month) ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($month) ?>" <?= $selected ?>><?= htmlspecialchars($display) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cari</button>
    </form>

    <h3>Tiket Baru</h3>
    <?php 
    render_table($tickets_baru_page, ($page_baru - 1) * $per_page + 1); 

    $base_url_baru = $_SERVER['PHP_SELF']
        . "?search=" . urlencode($search)
        . "&month=" . urlencode($filter_month)
        . "&page_proses=$page_proses"
        . "&page_selesai=$page_selesai";

    render_pagination($base_url_baru, $page_baru, $total_baru, $per_page, 'page_baru'); 
    ?>

    <h3>Tiket Dalam Proses</h3>
    <?php 
    render_table($tickets_proses_page, ($page_proses - 1) * $per_page + 1); 

    $base_url_proses = $_SERVER['PHP_SELF']
        . "?search=" . urlencode($search)
        . "&month=" . urlencode($filter_month)
        . "&page_baru=$page_baru"
        . "&page_selesai=$page_selesai";

    render_pagination($base_url_proses, $page_proses, $total_proses, $per_page, 'page_proses'); 
    ?>

    <h3>Tiket Selesai</h3>
    <?php 
    render_table($tickets_selesai_page, ($page_selesai - 1) * $per_page + 1); 

    $base_url_selesai = $_SERVER['PHP_SELF']
        . "?search=" . urlencode($search)
        . "&month=" . urlencode($filter_month)
        . "&page_baru=$page_baru"
        . "&page_proses=$page_proses";

    render_pagination($base_url_selesai, $page_selesai, $total_selesai, $per_page, 'page_selesai'); 
    ?>
</body>
</html>

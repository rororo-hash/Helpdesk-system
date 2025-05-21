<?php
session_start();

// Include fail auth dan functions
require_once 'includes/auth.php';      // untuk is_logged_in()
require_once 'includes/functions.php'; // untuk fungsi load_tickets(), update_ticket(), dsb

// Pastikan user sudah login
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Dapatkan ID tiket dari URL
$id = $_GET['id'] ?? '';

// Load semua tiket
$tickets = load_tickets();

// Cari tiket mengikut ID
$ticket = null;
foreach ($tickets as $t) {
    if ($t['id'] === $id) {
        $ticket = $t;
        break;
    }
}

if (!$ticket) {
    die("Tiket tidak ditemui.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = trim($_POST['case_id'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $part_status = trim($_POST['part_status'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Baru';

    // Validation mudah
    if ($case_id === '') $errors[] = "Case ID diperlukan.";
    if ($location === '') $errors[] = "Lokasi diperlukan.";
    if ($subject === '') $errors[] = "Subjek diperlukan.";
    if ($description === '') $errors[] = "Keterangan diperlukan.";

    // Jika tiada error, kemaskini tiket
    if (!$errors) {
        if (update_ticket($id, $case_id, $location, $part_status, $subject, $description, $status)) {
            header("Location: dashboard.php");  // <-- Tukar redirect ke sini
            exit();
        } else {
            $errors[] = "Gagal menyimpan perubahan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Edit Tiket #<?= htmlspecialchars($id) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fafafa; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 15px; padding: 10px 20px; background-color: #007BFF; border: none; color: white; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Edit Tiket #<?= htmlspecialchars($id) ?></h1>

    <?php if ($errors): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="edit_ticket.php?id=<?= urlencode($id) ?>">
        <label>Case ID:
            <input type="text" name="case_id" value="<?= htmlspecialchars($_POST['case_id'] ?? $ticket['case_id'] ?? '') ?>" required>
        </label>

        <label>Lokasi:
            <input type="text" name="location" value="<?= htmlspecialchars($_POST['location'] ?? $ticket['location'] ?? '') ?>" required>
        </label>

        <label>Status Part:
            <input type="text" name="part_status" value="<?= htmlspecialchars($_POST['part_status'] ?? $ticket['part_status'] ?? '') ?>">
        </label>

        <label>Subjek:
            <input type="text" name="subject" value="<?= htmlspecialchars($_POST['subject'] ?? $ticket['subject'] ?? '') ?>" required>
        </label>

        <label>Keterangan:
            <textarea name="description" rows="8" required><?= htmlspecialchars($_POST['description'] ?? $ticket['description'] ?? '') ?></textarea>
        </label>

        <label>Status:
            <select name="status" required>
                <option value="Baru" <?= (($_POST['status'] ?? $ticket['status']) === 'Baru') ? 'selected' : '' ?>>Baru</option>
                <option value="Dalam Proses" <?= (($_POST['status'] ?? $ticket['status']) === 'Dalam Proses') ? 'selected' : '' ?>>Dalam Proses</option>
                <option value="Selesai" <?= (($_POST['status'] ?? $ticket['status']) === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>
        </label>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <p><a href="dashboard.php">&laquo; Kembali ke Dashboard</a></p>
</body>
</html>

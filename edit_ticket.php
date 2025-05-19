<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

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
    die("Ticket not found.");
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = trim($_POST['case_id'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $part_status = trim($_POST['part_status'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Baru';

    if ($case_id === '') $errors[] = "Case ID diperlukan.";
    if ($location === '') $errors[] = "Lokasi diperlukan.";
    if ($subject === '') $errors[] = "Subjek diperlukan.";
    if ($description === '') $errors[] = "Keterangan diperlukan.";

    if (!$errors) {
        update_ticket($id, $case_id, $location, $part_status, $subject, $description, $status);
        header("Location: tickets.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Edit Tiket</title>
</head>
<body>
    <h1>Edit Tiket #<?= htmlspecialchars($id) ?></h1>

    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="edit_ticket.php?id=<?= urlencode($id) ?>">
        <label>Case ID:<br>
            <input type="text" name="case_id" value="<?= htmlspecialchars($ticket['case_id'] ?? '') ?>" required>
        </label><br><br>

        <label>Lokasi:<br>
            <input type="text" name="location" value="<?= htmlspecialchars($ticket['location'] ?? '') ?>" required>
        </label><br><br>

        <label>Status Part:<br>
            <input type="text" name="part_status" value="<?= htmlspecialchars($ticket['part_status'] ?? '') ?>">
        </label><br><br>

        <label>Subjek:<br>
            <input type="text" name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>" required>
        </label><br><br>

        <label>Keterangan:<br>
            <textarea name="description" rows="8" cols="60" required><?= htmlspecialchars($ticket['description']) ?></textarea>
        </label><br><br>

        <label>Status:<br>
            <select name="status">
                <option value="Baru" <?= $ticket['status'] === 'Baru' ? 'selected' : '' ?>>Baru</option>
                <option value="Dalam Proses" <?= $ticket['status'] === 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                <option value="Selesai" <?= $ticket['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </label><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
    <p><a href="tickets.php">Kembali ke Senarai Tiket</a></p>
</body>
</html>

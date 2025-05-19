<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = trim($_POST['case_id'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $part_status = trim($_POST['part_status'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($case_id === '') $errors[] = "Case ID diperlukan.";
    if ($location === '') $errors[] = "Lokasi diperlukan.";
    if ($part_status === '') $errors[] = "Status part diperlukan.";
    if ($subject === '') $errors[] = "Subjek diperlukan.";
    if ($description === '') $errors[] = "Penerangan diperlukan.";

    if (!$errors) {
        $tickets = load_tickets();
        $tickets[] = [
            'id' => uniqid(),
            'case_id' => $case_id,
            'location' => $location,
            'part_status' => $part_status,
            'subject' => $subject,
            'description' => $description,
            'status' => 'Baru',
            'created_at' => date('Y-m-d H:i:s')
        ];
        save_tickets($tickets);
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Tiket</title>
</head>
<body>
    <h1>Tambah Tiket Baru</h1>
    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post">
        <label>Case ID:<br>
            <input type="text" name="case_id" size="50" required>
        </label><br><br>
        <label>Lokasi:<br>
            <input type="text" name="location" size="50" required>
        </label><br><br>
        <label>Status Part:<br>
            <input type="text" name="part_status" size="50" required>
        </label><br><br>
        <label>Subjek:<br>
            <input type="text" name="subject" size="50" required>
        </label><br><br>
        <label>Penerangan:<br>
            <textarea name="description" rows="10" cols="50" required></textarea>
        </label><br><br>
        <button type="submit">Hantar Tiket</button>
    </form>
    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>

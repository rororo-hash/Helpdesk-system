<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$errors = [];
$case_id = '';
$location = '';
$part_status = '';
$subject = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = trim($_POST['case_id'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $part_status = trim($_POST['part_status'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($case_id === '') $errors[] = "Case ID diperlukan.";
    if ($location === '') $errors[] = "Lokasi diperlukan.";
    if ($part_status === '') $errors[] = "Status Part diperlukan.";
    if ($subject === '') $errors[] = "Subjek diperlukan.";
    if ($description === '') $errors[] = "Penerangan diperlukan.";

    if (!$errors) {
        create_ticket($case_id, $location, $part_status, $subject, $description);
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Tiket Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fafafa; }
        form { max-width: 500px; background: white; padding: 20px; box-shadow: 0 0 10px #ccc; border-radius: 5px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type=text], textarea {
            width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            margin-top: 15px; padding: 10px 15px; background: #007BFF; color: white; border: none; border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .errors { background: #fdd; border: 1px solid #f99; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Tambah Tiket Baru</h2>

    <?php if ($errors): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="case_id">Case ID:</label>
        <input type="text" id="case_id" name="case_id" value="<?= htmlspecialchars($case_id) ?>" required>

        <label for="location">Lokasi:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($location) ?>" required>

        <label for="part_status">Status Part:</label>
        <input type="text" id="part_status" name="part_status" value="<?= htmlspecialchars($part_status) ?>" required>

        <label for="subject">Subjek:</label>
        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" required>

        <label for="description">Penerangan:</label>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($description) ?></textarea>

        <button type="submit">Simpan Tiket</button>
    </form>

    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>

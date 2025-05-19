<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$errors = [];
$case_id = $location = $part_status = $subject = $description = '';

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
    <title>Tambah Tiket Baru</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 40px;
        }
        .container {
            background: #fff;
            padding: 25px 30px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            width: 420px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }
        form label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.8px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            resize: vertical;
            box-sizing: border-box;
            margin-bottom: 18px;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #4a90e2;
            outline: none;
        }
        button {
            width: 100%;
            background-color: #4a90e2;
            border: none;
            color: white;
            padding: 12px 0;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 600;
        }
        button:hover {
            background-color: #357ABD;
        }
        ul.errors {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            list-style: none;
        }
        ul.errors li {
            margin-bottom: 6px;
        }
        p.back-link {
            text-align: center;
            margin-top: 20px;
        }
        p.back-link a {
            text-decoration: none;
            color: #4a90e2;
            font-weight: 600;
        }
        p.back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Tiket Baru</h1>
        <?php if ($errors): ?>
            <ul class="errors">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" novalidate>
            <label for="case_id">Case ID:</label>
            <input type="text" id="case_id" name="case_id" required value="<?= htmlspecialchars($case_id) ?>">

            <label for="location">Lokasi:</label>
            <input type="text" id="location" name="location" required value="<?= htmlspecialchars($location) ?>">

            <label for="part_status">Status Part:</label>
            <input type="text" id="part_status" name="part_status" required value="<?= htmlspecialchars($part_status) ?>">

            <label for="subject">Subjek:</label>
            <input type="text" id="subject" name="subject" required value="<?= htmlspecialchars($subject) ?>">

            <label for="description">Penerangan:</label>
            <textarea id="description" name="description" rows="6" required><?= htmlspecialchars($description) ?></textarea>

            <button type="submit">Hantar Tiket</button>
        </form>
        <p class="back-link"><a href="dashboard.php">← Kembali ke Dashboard</a></p>
    </div>
</body>
</html>

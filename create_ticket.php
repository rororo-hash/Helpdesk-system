<?php
session_start();
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($subject === '') {
        $errors[] = "Subject is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }
    if (!$errors) {
        create_ticket($subject, $content);
        header("Location: tickets.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Ticket</title>
</head>
<body>
    <h1>Create New Ticket</h1>
    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="create_ticket.php">
        <label>Subject:<br>
            <input type="text" name="subject" size="50" required>
        </label><br><br>
        <label>Content:<br>
            <textarea name="content" rows="10" cols="50" required></textarea>
        </label><br><br>
        <button type="submit">Submit Ticket</button>
    </form>
    <p><a href="tickets.php">Back to tickets</a></p>
</body>
</html>

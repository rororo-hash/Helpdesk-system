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
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = $_POST['status'] ?? 'open';
    if ($subject === '') {
        $errors[] = "Subject is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }
    if (!$errors) {
        update_ticket($id, $subject, $content, $status);
        header("Location: tickets.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Ticket</title>
</head>
<body>
    <h1>Edit Ticket #<?php echo htmlspecialchars($id) ?></h1>
    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="edit_ticket.php?id=<?php echo urlencode($id) ?>">
        <label>Subject:<br>
            <input type="text" name="subject" size="50" required value="<?php echo htmlspecialchars($ticket['subject']) ?>">
        </label><br><br>
        <label>Content:<br>
            <textarea name="content" rows="10" cols="50" required><?php echo htmlspecialchars($ticket['content']) ?></textarea>
        </label><br><br>
        <label>Status:<br>
            <select name="status">
                <option value="open" <?php if ($ticket['status'] === 'open') echo 'selected' ?>>Open</option>
                <option value="closed" <?php if ($ticket['status'] === 'closed') echo 'selected' ?>>Closed</option>
            </select>
        </label><br><br>
        <button type="submit">Save Changes</button>
    </form>
    <p><a href="tickets.php">Back to tickets</a></p>
</body>
</html>

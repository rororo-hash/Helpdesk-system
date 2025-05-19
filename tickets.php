<?php
require_once 'includes/functions.php';
session_start();

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$tickets = load_tickets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Helpdesk Tickets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 0.5em; text-align: left; }
        th { background-color: #f2f2f2; }
        .closed { color: gray; text-decoration: line-through; }
        a.button { padding: 0.3em 0.7em; background: #28a745; color: white; text-decoration: none; border-radius: 4px; }
        a.button.close { background: #dc3545; }
    </style>
</head>
<body>
    <h1>Helpdesk Tickets</h1>
    <p><a href="create_ticket.php" class="button">Create New Ticket</a> | <a href="logout.php">Logout</a></p>
    <table>
        <tr><th>ID</th><th>Subject</th><th>Status</th><th>Created</th><th>Actions</th></tr>
        <?php foreach ($tickets as $ticket): ?>
            <tr class="<?php echo $ticket['status'] === 'closed' ? 'closed' : '' ?>">
                <td><?php echo htmlspecialchars($ticket['id']) ?></td>
                <td><?php echo htmlspecialchars($ticket['subject']) ?></td>
                <td><?php echo htmlspecialchars($ticket['status']) ?></td>
                <td><?php echo htmlspecialchars($ticket['created_at']) ?></td>
                <td>
                    <a href="edit_ticket.php?id=<?php echo urlencode($ticket['id']) ?>" class="button">Edit</a>
                    <?php if ($ticket['status'] !== 'closed'): ?>
                    <a href="close_ticket.php?id=<?php echo urlencode($ticket['id']) ?>" class="button close" onclick="return confirm('Are you sure to close this ticket?')">Close</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

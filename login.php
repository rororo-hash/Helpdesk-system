<?php
session_start();
require_once 'includes/functions.php';

if (is_logged_in()) {
    header("Location: tickets.php");
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if (check_login($user, $pass)) {
        $_SESSION['logged_in'] = true;
        header("Location: tickets.php");
        exit();
    } else {
        $errors[] = "Invalid username or password.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - Helpdesk</title>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="login.php">
        <label>Username: <input type="text" name="username" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
session_start();
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if (check_login($user, $pass)) {
        $_SESSION['logged_in'] = true;
        header("Location: tickets.php");
        exit();
    } else {
        $error = "Nama pengguna atau kata laluan salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Helpdesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #c3dafe, #a5b4fc);
            margin: 0; padding: 0;
            height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #4f46e5;
        }
        label { font-weight: 600; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            background-color: #4f46e5;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .show-password {
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login Admin</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Nama Pengguna</label>
        <input type="text" name="username" required>

        <label for="password">Kata Laluan</label>
        <input type="password" name="password" id="password" required>

        <div class="show-password">
            <input type="checkbox" id="togglePassword">
            <label for="togglePassword" style="margin: 0;">Tunjuk Kata Laluan</label>
        </div>

        <button type="submit">Log Masuk</button>
    </form>
    <div class="footer">Helpdesk System © <?= date("Y") ?></div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('change', function () {
        passwordInput.type = this.checked ? 'text' : 'password';
    });
</script>
</body>
</html>

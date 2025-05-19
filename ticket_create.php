<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($subject && $description) {
        $tickets = getTickets();

        $newTicket = [
            'id' => generateTicketId(),
            'subject' => $subject,
            'description' => $description,
            'status' => 'Baru',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $tickets[] = $newTicket;
        saveTickets($tickets);

        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Subjek dan penerangan diperlukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Tambah Tiket Baru</title></head>
<body>
<h2>Tambah Tiket Baru</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Subjek: <input type="text" name="subject" required><br><br>
    Penerangan:<br>
    <textarea name="description" rows="5" cols="50" required></textarea><br><br>
    <button type="submit">Hantar Tiket</button>
</form>

<p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>

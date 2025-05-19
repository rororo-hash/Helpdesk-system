<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $description = $_POST['description'] ?? '';
    $case_id = $_POST['case_id'] ?? '';
    $location = $_POST['location'] ?? '';
    $part_status = $_POST['part_status'] ?? '';

    if ($subject && $description && $case_id && $location && $part_status) {
        $tickets = getTickets();

        $newTicket = [
            'id' => generateTicketId(),
            'case_id' => $case_id,
            'location' => $location,
            'part_status' => $part_status,
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
        $error = "Semua maklumat diperlukan.";
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
    Case ID: <input type="text" name="case_id" required><br><br>
    Lokasi: <input type="text" name="location" required><br><br>
    Status Part: <input type="text" name="part_status" required><br><br>
    Subjek: <input type="text" name="subject" required><br><br>
    Penerangan:<br>
    <textarea name="description" rows="5" cols="50" required></textarea><br><br>
    <button type="submit">Hantar Tiket</button>
</form>

<p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>
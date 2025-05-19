<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$tickets = getTickets();

// Cari tiket ikut ID
$ticketIndex = null;
foreach ($tickets as $index => $t) {
    if ($t['id'] === $id) {
        $ticketIndex = $index;
        break;
    }
}

if ($ticketIndex === null) {
    echo "Tiket tidak dijumpai.";
    exit;
}

$ticket = $tickets[$ticketIndex];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? $ticket['status'];

    if ($subject && $description && in_array($status, ['Baru', 'Dalam Tindakan', 'Selesai', 'Ditutup'])) {
        $tickets[$ticketIndex]['subject'] = $subject;
        $tickets[$ticketIndex]['description'] = $description;
        $tickets[$ticketIndex]['status'] = $status;
        $tickets[$ticketIndex]['updated_at'] = date('Y-m-d H:i:s');

        saveTickets($tickets);

        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Sila lengkapkan semua maklumat dengan betul.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Tiket <?= htmlspecialchars($ticket['id']) ?></title></head>
<body>
<h2>Edit Tiket <?= htmlspecialchars($ticket['id']) ?></h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Subjek: <input type="text" name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>" required><br><br>
    Penerangan:<br>
    <textarea name="description" rows="5" cols="50" required><?= htmlspecialchars($ticket['description']) ?></textarea><br><br>
    Status:
    <select name="status" required>
        <?php
        $statuses = ['Baru', 'Dalam Tindakan', 'Selesai', 'Ditutup'];
        foreach ($statuses as $s) {
            $sel = ($ticket['status'] === $s) ? 'selected' : '';
            echo "<option value="$s" $sel>$s</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Simpan Perubahan</button>
</form>

<p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>

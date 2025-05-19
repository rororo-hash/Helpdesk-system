<?php
session_start();
require_once 'includes/functions.php';
require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buat Tiket Baharu</title>
    <style>
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 15px; padding: 10px 15px; background-color: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Buat Tiket Baharu</h2>
    <form method="POST" action="save_ticket.php">
        <label for="case_id">Case ID</label>
        <input type="text" name="case_id" id="case_id" required>

        <label for="location">Lokasi</label>
        <input type="text" name="location" id="location" required>

        <label for="part_status">Status Part</label>
        <select name="part_status" id="part_status" required>
            <option value="">-- Pilih Status Part --</option>
            <option value="Ada">Ada</option>
            <option value="Tiada">Tiada</option>
            <option value="Menunggu">Menunggu</option>
        </select>

        <label for="subject">Subjek</label>
        <input type="text" name="subject" id="subject" required>

        <label for="description">Penerangan</label>
        <textarea name="description" id="description" rows="5" required></textarea>

        <button type="submit">Hantar Tiket</button>
    </form>
    <br>
    <a href="tickets.php">Kembali ke Senarai Tiket</a>
</body>
</html>
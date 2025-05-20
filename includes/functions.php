<?php

define('TICKET_FILE', __DIR__ . '/../data/tickets.json');

// Admin login credentials from env or default
$ADMIN_USER = getenv('ADMIN_USER') ?: 'admin';
$ADMIN_PASS = getenv('ADMIN_PASS') ?: 'password123';

// Fungsi untuk baca tiket
function load_tickets() {
    if (!file_exists(TICKET_FILE)) {
        file_put_contents(TICKET_FILE, json_encode([]));
    }
    $json = file_get_contents(TICKET_FILE);
    $tickets = json_decode($json, true);
    return is_array($tickets) ? $tickets : [];
}

// Fungsi untuk simpan tiket
function save_tickets($tickets) {
    return file_put_contents(TICKET_FILE, json_encode($tickets, JSON_PRETTY_PRINT)) !== false;
}

// Fungsi untuk semak login
function is_logged_in() {
    return !empty($_SESSION['admin']);
}

// Semak maklumat login admin
function check_login($username, $password) {
    global $ADMIN_USER, $ADMIN_PASS;
    if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
        $_SESSION['admin'] = true;
        return true;
    }
    return false;
}

// Fungsi untuk logout
function logout() {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

// Jana ID unik
function generateTicketId() {
    return uniqid('TIKET-');
}

// Cipta tiket baru
function create_ticket($case_id, $location, $part_status, $subject, $description) {
    $tickets = load_tickets();
    $tickets[] = [
        'id' => generateTicketId(),
        'case_id' => $case_id,
        'location' => $location,
        'part_status' => $part_status,
        'subject' => $subject,
        'description' => $description,
        'status' => 'Baru',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    return save_tickets($tickets);
}

// Kemaskini tiket
function update_ticket($id, $case_id, $location, $part_status, $subject, $description, $status) {
    $tickets = load_tickets();
    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['case_id'] = $case_id;
            $ticket['location'] = $location;
            $ticket['part_status'] = $part_status;
            $ticket['subject'] = $subject;
            $ticket['description'] = $description;
            $ticket['status'] = $status;
            break;
        }
    }
    return save_tickets($tickets);
}

// Tutup tiket
function close_ticket($id) {
    $tickets = load_tickets();
    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['status'] = 'Selesai';
            break;
        }
    }
    return save_tickets($tickets);
}

// Padam tiket
function delete_ticket($id) {
    $tickets = load_tickets();
    $tickets = array_filter($tickets, fn($t) => $t['id'] !== $id);
    return save_tickets(array_values($tickets)); // reindex semula
}

// Dapatkan tiket ikut ID
function get_ticket_by_id($id) {
    $tickets = load_tickets();
    foreach ($tickets as $ticket) {
        if ($ticket['id'] === $id) {
            return $ticket;
        }
    }
    return null;
}

// Warna status
function statusColor($status) {
    return match(strtolower($status)) {
        'baru' => 'blue',
        'selesai' => 'green',
        'dalam proses' => 'orange',
        default => 'gray'
    };
}

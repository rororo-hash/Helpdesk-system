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

function statusColor($status) {
    return match(strtolower($status)) {
        'baru' => 'blue',
        'selesai' => 'green',
        'dalam proses' => 'orange',
        default => 'gray'
    };
}

// Fungsi untuk simpan tiket
function save_tickets($tickets) {
    file_put_contents(TICKET_FILE, json_encode($tickets, JSON_PRETTY_PRINT));
}

// Semak login
function is_logged_in() {
    return !empty($_SESSION['logged_in']);
}

// Semak maklumat login admin
function check_login($username, $password) {
    global $ADMIN_USER, $ADMIN_PASS;
    return $username === $ADMIN_USER && $password === $ADMIN_PASS;
}

// Jana ID unik
function generateTicketId() {
    return uniqid('TIKET-');
}

// Cipta tiket baru
function create_ticket($case_id, $location, $part_status, $subject, $description) {
    $tickets = load_tickets();
    $tickets[] = [
        'id' => uniqid(),
        'case_id' => $case_id,
        'location' => $location,
        'part_status' => $part_status,
        'subject' => $subject,
        'description' => $description,
        'status' => 'Baru',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    save_tickets($tickets);
}

// Kemaskini tiket sedia ada
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
    save_tickets($tickets);
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
    save_tickets($tickets);
}

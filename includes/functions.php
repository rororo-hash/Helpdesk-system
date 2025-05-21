<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('TICKET_FILE', __DIR__ . '/../data/tickets.json');
define('USER_FILE', __DIR__ . '/../data/users.json');

// ----------------------
// Fungsi Login
// ----------------------
function check_login($username, $password) {
    if (!file_exists(USER_FILE)) return false;

    $users = json_decode(file_get_contents(USER_FILE), true);
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

function logout() {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

// ----------------------
// Fungsi Tiket
// ----------------------
function load_tickets() {
    if (!file_exists(TICKET_FILE)) {
        file_put_contents(TICKET_FILE, json_encode([]));
    }
    $json = file_get_contents(TICKET_FILE);
    $tickets = json_decode($json, true);
    return is_array($tickets) ? $tickets : [];
}

function save_tickets($tickets) {
    return file_put_contents(TICKET_FILE, json_encode($tickets, JSON_PRETTY_PRINT)) !== false;
}

function generateTicketId() {
    return uniqid('TIKET-');
}

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

function delete_ticket($id) {
    $tickets = load_tickets();
    $tickets = array_filter($tickets, fn($t) => $t['id'] !== $id);
    return save_tickets(array_values($tickets)); // reindex semula
}

function get_ticket_by_id($id) {
    $tickets = load_tickets();
    foreach ($tickets as $ticket) {
        if ($ticket['id'] === $id) {
            return $ticket;
        }
    }
    return null;
}

function statusColor($status) {
    return match(strtolower($status)) {
        'baru' => 'blue',
        'selesai' => 'green',
        'dalam proses' => 'orange',
        default => 'gray'
    };
}

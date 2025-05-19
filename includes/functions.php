<?php

define('TICKET_FILE', __DIR__ . '/../data/tickets.json');

// Senarai pengguna: username => password
$ADMIN_USERS = [
    'admin' => 'admin123',
    'faiz' => '123',
    'support' => 'helpdesk'
];

function check_login($username, $password) {
    global $ADMIN_USERS;
    return isset($ADMIN_USERS[$username]) && $ADMIN_USERS[$username] === $password;
}

function load_tickets() {
    if (!file_exists(TICKET_FILE)) {
        file_put_contents(TICKET_FILE, json_encode([]));
    }
    $json = file_get_contents(TICKET_FILE);
    $tickets = json_decode($json, true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
    return $tickets;
}

function save_tickets($tickets) {
    file_put_contents(TICKET_FILE, json_encode($tickets, JSON_PRETTY_PRINT));
}

function is_logged_in() {
    return !empty($_SESSION['logged_in']);

}

function create_ticket($subject, $content) {
    $tickets = load_tickets();
    $id = uniqid();
    $tickets[] = [
        'id' => $id,
        'subject' => $subject,
        'content' => $content,
        'status' => 'open',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    save_tickets($tickets);
}

function update_ticket($id, $subject, $content, $status) {
    $tickets = load_tickets();
    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['subject'] = $subject;
            $ticket['content'] = $content;
            $ticket['status'] = $status;
            break;
        }
    }
    save_tickets($tickets);
}

function close_ticket($id) {
    $tickets = load_tickets();
    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['status'] = 'closed';
            break;
        }
    }
    save_tickets($tickets);
}

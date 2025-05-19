<?php

define('TICKET_FILE', __DIR__ . '/../data/tickets.json');

// Dapatkan senarai tiket
function getTickets() {
    if (!file_exists(TICKET_FILE)) {
        return [];
    }
    $json = file_get_contents(TICKET_FILE);
    return json_decode($json, true) ?? [];
}

// Simpan senarai tiket
function saveTickets($tickets) {
    $json = json_encode($tickets, JSON_PRETTY_PRINT);
    file_put_contents(TICKET_FILE, $json);
}

// Hasilkan ID unik
function generateTicketId() {
    return uniqid('TKT-');
}

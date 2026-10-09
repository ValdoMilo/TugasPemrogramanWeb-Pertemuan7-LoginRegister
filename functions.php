<?php
session_start();

define('JSON_FILE', 'users.json');

function getUsers() {
    if (!file_exists(JSON_FILE)) {
        file_put_contents(JSON_FILE, json_encode([]));
    }
    $data = file_get_contents(JSON_FILE);
    return json_decode($data, true) ?: [];
}

function saveUsers($users) {
    file_put_contents(JSON_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
?>
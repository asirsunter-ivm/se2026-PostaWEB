<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
 
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}
 
function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Duhet te hyni ne sistem.']);
        exit;
    }
}
 
function requireAdmin() {
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Nuk keni akses.']);
        exit;
    }
}
 
function getCurrentUser() {
    return [
        'id'        => $_SESSION['user_id']   ?? null,
        'full_name' => $_SESSION['full_name'] ?? null,
        'email'     => $_SESSION['email']     ?? null,
        'role'      => $_SESSION['role']      ?? null,
    ];
}
 ?>
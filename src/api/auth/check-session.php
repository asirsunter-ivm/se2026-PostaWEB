<?php
require_once __DIR__ . '/../../includes/session.php';
 
header('Content-Type: application/json; charset=utf-8');
 
if (isLoggedIn()) {
    echo json_encode([
        'loggedIn'  => true,
        'user_id'   => $_SESSION['user_id'],
        'full_name' => $_SESSION['full_name'],
        'email'     => $_SESSION['email'],
        'role'      => $_SESSION['role']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
 ?>
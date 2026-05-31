<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';

header('Content-Type: application/json');
requireLogin();

$oldPassword = $_POST['old_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

if (strlen($newPassword) < 8) {
    echo json_encode(['success' => false, 'message' => 'Fjalekalimi i ri duhet te kete min 8 karaktere.']);
    exit;
}

$stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!password_verify($oldPassword, $user['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Fjalekalimi aktual eshte i gabuar.']);
    exit;
}

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
$stmt->execute([$newHash, $_SESSION['user_id']]);

echo json_encode(['success' => true, 'message' => 'Fjalekalimi u ndryshua me sukses!']);
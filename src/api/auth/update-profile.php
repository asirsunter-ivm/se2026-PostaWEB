<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/crypto.php';

header('Content-Type: application/json');
requireLogin();

$fullName = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($fullName) || strlen($fullName) < 2) {
    echo json_encode(['success' => false, 'message' => 'Emri duhet te kete min 2 karaktere.']);
    exit;
}

$phoneEnc = !empty($phone) ? encryptData($phone) : null;

$stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone_encrypted = ? WHERE id = ?');
$stmt->execute([$fullName, $phoneEnc, $_SESSION['user_id']]);

$_SESSION['full_name'] = $fullName;

echo json_encode(['success' => true, 'message' => 'Profili u perditesua me sukses!']);
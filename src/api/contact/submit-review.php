<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';

header('Content-Type: application/json; charset=utf-8');

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']  ?? '');
$rating  = (int)($_POST['rating'] ?? 0);
$message = trim($_POST['message'] ?? '');

// Validim
if (empty($name) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Te gjitha fushat jane te detyrueshme.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email i pavlefshem.']);
    exit;
}
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Vleresimi duhet te jete 1-5.']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare(
    'INSERT INTO reviews (user_id, name, email, rating, message)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$userId, $name, $email, $rating, $message]);

echo json_encode([
    'success' => true,
    'message' => 'Faleminderit per vleresimin!'
]);
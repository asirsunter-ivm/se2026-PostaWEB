<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
header('Content-Type: application/json');
requireAdmin();

$id = (int)($_POST['id'] ?? 0);
if ($id === (int)$_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Nuk mund te fshini veten.']);
    exit;
}
$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([$id]);
echo json_encode(['success' => true]);

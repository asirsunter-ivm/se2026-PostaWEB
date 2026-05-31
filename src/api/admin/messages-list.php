<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
header('Content-Type: application/json');
requireAdmin();

$stmt = $pdo->query('SELECT id, name, email, subject, message, is_read, DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") AS created_at FROM contact_messages ORDER BY created_at DESC');
echo json_encode(['success' => true, 'messages' => $stmt->fetchAll()]);

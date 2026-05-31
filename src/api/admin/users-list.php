<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
header('Content-Type: application/json');
requireAdmin();

$stmt = $pdo->query('SELECT id, full_name, email, role, is_active, DATE_FORMAT(created_at, "%d/%m/%Y") AS created_at FROM users ORDER BY created_at DESC');
echo json_encode(['success' => true, 'users' => $stmt->fetchAll()]);

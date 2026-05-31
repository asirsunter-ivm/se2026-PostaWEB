<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
requireLogin();

$q      = '%' . trim($_GET['q'] ?? '') . '%';
$status = $_GET['status'] ?? '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;

$where  = ['(p.tracking_code LIKE ? OR p.receiver_name LIKE ?)'];
$params = [$q, $q];

if (!isAdmin()) {
    $where[] = 'p.sender_id = ?';
    $params[] = $_SESSION['user_id'];
}

$validStatuses = ['created','picked_up','in_transit','out_for_delivery','delivered','returned','cancelled'];
if (!empty($status) && in_array($status, $validStatuses)) {
    $where[] = 'p.current_status = ?';
    $params[] = $status;
}

$whereSql = implode(' AND ', $where);

$sql = "
    SELECT p.id, p.tracking_code, p.receiver_name, p.weight_kg, p.shipping_cost,
           p.current_status, p.payment_status, p.created_at,
           pt.type_name AS package_type
    FROM packages p
    INNER JOIN package_types pt ON pt.id = p.package_type_id
    WHERE {$whereSql}
    ORDER BY p.created_at DESC
    LIMIT {$limit} OFFSET {$offset}
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$packages = $stmt->fetchAll();

foreach ($packages as &$p) {
    $p['created_at']   = formatDate($p['created_at']);
    $p['status_label'] = statusToAlbanian($p['current_status']);
}

echo json_encode(['success' => true, 'packages' => $packages]);
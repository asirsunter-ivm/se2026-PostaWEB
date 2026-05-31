<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';

header('Content-Type: application/json');
requireAdmin();

$packageId = (int)($_POST['package_id'] ?? 0);
$status    = $_POST['status'] ?? '';
$location  = $_POST['location'] ?? '';
$note      = $_POST['note'] ?? '';

if ($packageId === 0) {
    echo json_encode(['success' => false, 'message' => 'ID e pavlefshme.']);
    exit;
}

// Nese eshte "paid", bej vetem update te payment_status
if ($status === 'paid') {
    $stmt = $pdo->prepare('UPDATE packages SET payment_status = "paid" WHERE id = ?');
    $stmt->execute([$packageId]);

    $stmt = $pdo->prepare('INSERT INTO tracking_history (package_id, status, location, note, updated_by) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$packageId, 'created', $location, 'Pagesa u konfirmua manualisht: ' . $note, $_SESSION['user_id']]);

    echo json_encode(['success' => true, 'message' => 'Pakoja u shenua si e paguar.']);
    exit;
}

$valid = ['picked_up','in_transit','out_for_delivery','delivered','returned','cancelled'];
if (!in_array($status, $valid)) {
    echo json_encode(['success' => false, 'message' => 'Status i pavlefshem.']);
    exit;
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('UPDATE packages SET current_status = ?, delivered_at = IF(? = "delivered", NOW(), delivered_at) WHERE id = ?');
    $stmt->execute([$status, $status, $packageId]);

    $stmt = $pdo->prepare('INSERT INTO tracking_history (package_id, status, location, note, updated_by) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$packageId, $status, $location, $note, $_SESSION['user_id']]);

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

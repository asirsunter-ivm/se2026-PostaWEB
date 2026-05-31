<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$tracking = trim($_GET['tn'] ?? '');

if (empty($tracking)) {
    echo json_encode(['success' => false, 'message' => 'Ju lutem fusni nje kod tracking.']);
    exit;
}

if (!preg_match('/^PW[A-Z0-9]+$/', $tracking)) {
    echo json_encode(['success' => false, 'message' => 'Kodi vendosur eshte i pavlefshem.']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT p.tracking_code, p.receiver_name, p.weight_kg, p.current_status,
               p.created_at, p.delivered_at,
               pt.type_name AS package_type,
               rc.name AS receiver_city,
               rcn.name AS receiver_country,
               sc.name AS sender_city,
               scn.name AS sender_country,
               u.full_name AS sender_name
        FROM packages p
        INNER JOIN package_types pt ON pt.id = p.package_type_id
        INNER JOIN cities rc        ON rc.id = p.receiver_city_id
        INNER JOIN countries rcn    ON rcn.id = rc.country_id
        INNER JOIN users u          ON u.id = p.sender_id
        LEFT JOIN cities sc         ON sc.id = u.city_id
        LEFT JOIN countries scn     ON scn.id = sc.country_id
        WHERE p.tracking_code = ?
    ');
    $stmt->execute([$tracking]);
    $package = $stmt->fetch();

    if (!$package) {
        echo json_encode(['success' => false, 'message' => 'Asnje pako nuk u gjet.']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT status, location, note, created_at
        FROM tracking_history
        WHERE package_id = (SELECT id FROM packages WHERE tracking_code = ?)
        ORDER BY created_at ASC
    ');
    $stmt->execute([$tracking]);
    $history = $stmt->fetchAll();

    $package['created_at']   = formatDate($package['created_at']);
    $package['delivered_at'] = formatDate($package['delivered_at']);
    $package['status_label'] = statusToAlbanian($package['current_status']);

    foreach ($history as &$h) {
        $h['created_at']   = formatDate($h['created_at']);
        $h['status_label'] = statusToAlbanian($h['status']);
    }

    echo json_encode([
        'success' => true,
        'package' => $package,
        'history' => $history
    ]);
} catch (PDOException $e) {
    error_log('Track error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Gabim ne server.']);
}
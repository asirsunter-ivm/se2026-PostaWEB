<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
requireLogin();

$body      = json_decode(file_get_contents('php://input'), true);
$packageId = (int)($body['package_id'] ?? 0);

if ($packageId === 0) {
    echo json_encode(['error' => 'Package ID mungon.']);
    exit;
}

// Merr pakon dhe verifiko ownership
$stmt = $pdo->prepare('SELECT tracking_code, shipping_cost FROM packages WHERE id = ? AND sender_id = ?');
$stmt->execute([$packageId, $_SESSION['user_id']]);
$package = $stmt->fetch();

if (!$package) {
    echo json_encode(['error' => 'Pakoja nuk u gjet.']);
    exit;
}

// PayPal OAuth Token
$ch = curl_init('https://api-m.sandbox.paypal.com/v1/oauth2/token');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD        => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
    CURLOPT_HTTPHEADER     => ['Accept: application/json']
]);
$tokenRes = json_decode(curl_exec($ch), true);
curl_close($ch);

$token = $tokenRes['access_token'] ?? null;
if (!$token) {
    echo json_encode(['error' => 'PayPal autentikimi deshtoi.']);
    exit;
}

// Krijo Order
$ch = curl_init('https://api-m.sandbox.paypal.com/v2/checkout/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ],
    CURLOPT_POSTFIELDS     => json_encode([
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'reference_id' => $package['tracking_code'],
            'amount' => [
                'currency_code' => 'USD',
                'value' => number_format($package['shipping_cost'], 2, '.', '')
            ]
        ]]
    ])
]);
$order = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($order['id'])) {
    echo json_encode(['error' => 'PayPal order krijim deshtoi.']);
    exit;
}

echo json_encode(['orderID' => $order['id']]);
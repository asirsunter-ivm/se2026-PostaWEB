<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');
requireLogin();

$body      = json_decode(file_get_contents('php://input'), true);
$orderID   = $body['orderID']    ?? '';
$packageId = (int)($body['package_id'] ?? 0);

if (empty($orderID) || $packageId === 0) {
    echo json_encode(['success' => false, 'message' => 'Te dhena te pasakta.']);
    exit;
}

// Verifiko ownership
$stmt = $pdo->prepare('
    SELECT p.*, u.email AS sender_email, u.full_name AS sender_name
    FROM packages p
    INNER JOIN users u ON u.id = p.sender_id
    WHERE p.id = ? AND p.sender_id = ?
');
$stmt->execute([$packageId, $_SESSION['user_id']]);
$package = $stmt->fetch();

if (!$package) {
    echo json_encode(['success' => false, 'message' => 'Pakoja nuk u gjet.']);
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
    echo json_encode(['success' => false, 'message' => 'PayPal autentikimi deshtoi.']);
    exit;
}

// Capture pagesen
$ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => '{}',
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]
]);
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

if (($result['status'] ?? '') !== 'COMPLETED') {
    echo json_encode(['success' => false, 'message' => 'Pagesa nuk u konfirmua.']);
    exit;
}

// Update DB
$stmt = $pdo->prepare('UPDATE packages SET payment_status = "paid", payment_id = ? WHERE id = ?');
$stmt->execute([$orderID, $packageId]);

// Dergo email konfirmimi
$body = emailTemplate('Pagesa u konfirmua! 🎉', "
    <p>Pershendetje <strong>" . htmlspecialchars($package['sender_name']) . "</strong>,</p>
    <p>Pagesa juaj per pakon <strong>{$package['tracking_code']}</strong> u konfirmua me sukses.</p>
    <p><strong>Shuma e Paguar:</strong> \${$package['shipping_cost']}</p>
    <p><strong>PayPal Order ID:</strong> {$orderID}</p>
    <p>Etiketa e pakos eshte gati per shkarkim ne dashboard-in tuaj.</p>
");
sendEmail($package['sender_email'], "PostaWeb - Pagesa u konfirmua! Kodi: {$package['tracking_code']}", $body);

echo json_encode([
    'success'    => true,
    'message'    => 'Pagesa u konfirmua me sukses!',
    'package_id' => $packageId
]);
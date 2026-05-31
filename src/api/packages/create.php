<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/crypto.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode e gabuar.']);
    exit;
}

$sender_name      = trim($_POST['sender_name']      ?? '');
$sender_address   = trim($_POST['sender_address']   ?? '');
$sender_city_id   = (int)($_POST['sender_city_id']  ?? 0);
$receiver_name    = trim($_POST['receiver_name']    ?? '');
$receiver_email   = trim($_POST['receiver_email']   ?? '');
$receiver_address = trim($_POST['receiver_address'] ?? '');
$receiver_city_id = (int)($_POST['receiver_city_id']?? 0);
$receiver_phone   = trim($_POST['receiver_phone']   ?? '');
$package_type_id  = (int)($_POST['package_type_id'] ?? 0);
$weight_kg        = (float)($_POST['weight_kg']     ?? 0);
$declared_value   = (float)($_POST['declared_value']?? 0);
$description      = trim($_POST['description']      ?? '');

// Validim
if (empty($sender_name) || empty($receiver_name) || empty($receiver_address)) {
    echo json_encode(['success' => false, 'message' => 'Te dhena te detyrueshme mungojne.']);
    exit;
}
if ($weight_kg <= 0) {
    echo json_encode(['success' => false, 'message' => 'Pesha duhet te jete > 0.']);
    exit;
}

// Merr country IDs
$stmtCity = $pdo->prepare('SELECT country_id FROM cities WHERE id = ?');
$stmtCity->execute([$sender_city_id]);
$senderCountryId = $stmtCity->fetchColumn() ?: 1;
$stmtCity->execute([$receiver_city_id]);
$receiverCountryId = $stmtCity->fetchColumn() ?: 1;

$shippingCost = calculateShippingCost($weight_kg, $package_type_id, $senderCountryId, $receiverCountryId, $pdo);
$trackingCode = generateTrackingCode();
$receiverAddressEnc = encryptData($receiver_address);
$receiverPhoneEnc   = !empty($receiver_phone) ? encryptData($receiver_phone) : null;

$stmt = $pdo->prepare('
    INSERT INTO packages (
        tracking_code, sender_id, receiver_name, receiver_email,
        receiver_phone_encrypted, receiver_address_encrypted, receiver_city_id,
        package_type_id, weight_kg, declared_value, description,
        shipping_cost, current_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "created")
');
$stmt->execute([
    $trackingCode, $_SESSION['user_id'], $receiver_name, $receiver_email,
    $receiverPhoneEnc, $receiverAddressEnc, $receiver_city_id ?: null,
    $package_type_id, $weight_kg, $declared_value, $description, $shippingCost
]);
$packageId = $pdo->lastInsertId();

// Tracking history
$stmtH = $pdo->prepare('
    INSERT INTO tracking_history (package_id, status, location, note, updated_by)
    VALUES (?, "created", ?, "Pakoja u krijua", ?)
');
$stmtH->execute([$packageId, $sender_name, $_SESSION['user_id']]);

// Email sender
$emailBody = emailTemplate('Pakoja u krijua!', "
    <p>Pershendetje <strong>" . htmlspecialchars($_SESSION['full_name']) . "</strong>,</p>
    <p>Pakoja juaj u krijua me sukses.</p>
    <p><strong>Kodi:</strong> {$trackingCode}</p>
    <p><strong>Kostoja:</strong> \${$shippingCost}</p>
    <p>Ju lutem paguani per te aktivizuar pakon.</p>
");
sendEmail($_SESSION['email'], "PostaWeb - Pakoja {$trackingCode}", $emailBody);

// Email receiver
if (!empty($receiver_email)) {
    $emailReceiver = emailTemplate('Nje pako vjen per ju!', "
        <p>Pershendetje <strong>" . htmlspecialchars($receiver_name) . "</strong>,</p>
        <p>" . htmlspecialchars($_SESSION['full_name']) . " ju ka derguar nje pako.</p>
        <p><strong>Kodi i gjurmimit:</strong> {$trackingCode}</p>
    ");
    sendEmail($receiver_email, "PostaWeb - Nje pako vjen per ju!", $emailReceiver);
}

echo json_encode([
    'success'       => true,
    'message'       => 'Pakoja u krijua me sukses!',
    'package_id'    => $packageId,
    'tracking_code' => $trackingCode,
    'shipping_cost' => $shippingCost
]);
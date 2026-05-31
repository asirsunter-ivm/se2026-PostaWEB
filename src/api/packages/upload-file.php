<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';

header('Content-Type: application/json; charset=utf-8');

requireLogin();

$pkgId = (int)($_POST['package_id'] ?? 0);
if ($pkgId === 0) {
    echo json_encode(['success' => false, 'message' => 'Package ID mungon.']);
    exit;
}

// Verifiko ownership
$stmt = $pdo->prepare('SELECT id FROM packages WHERE id = ? AND sender_id = ?');
$stmt->execute([$pkgId, $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Nuk keni akses ne kete pako.']);
    exit;
}

// Kontrollo file
if (empty($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'Nuk u zgjodh asnje file.']);
    exit;
}

$file    = $_FILES['file'];
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
$maxSize = 5 * 1024 * 1024; // 5MB

if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Lloji i file-it nuk lejohet. (JPG, PNG, PDF)']);
    exit;
}
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File-i eshte shume i madh (max 5MB).']);
    exit;
}

// Emër i sigurt
$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$safeName = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
$dir      = __DIR__ . '/../../uploads/packages/';

if (!is_dir($dir)) mkdir($dir, 0755, true);

if (!move_uploaded_file($file['tmp_name'], $dir . $safeName)) {
    echo json_encode(['success' => false, 'message' => 'Ngarkimi deshtoi.']);
    exit;
}

// Ruaj ne DB
$stmt = $pdo->prepare(
    'INSERT INTO package_files (package_id, file_name, file_path, file_type)
     VALUES (?, ?, ?, ?)'
);
$stmt->execute([
    $pkgId,
    htmlspecialchars($file['name']),
    'uploads/packages/' . $safeName,
    $file['type']
]);

echo json_encode([
    'success' => true,
    'message' => 'File-i u ngarkua me sukses!',
    'path'    => 'uploads/packages/' . $safeName
]);
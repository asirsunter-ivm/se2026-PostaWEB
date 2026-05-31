<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
 
header('Content-Type: application/json; charset=utf-8');
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode e gabuar.']);
    exit;
}
 
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';
 
// Validim bazik
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email ose fjalekalim i pavlefshem.']);
    exit;
}
 
// Kerko perdoruesin
$stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role, is_active FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
 
// Kontroll kredenciale
if (!$user || !password_verify($password, $user['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Email ose fjalekalim i gabuar.']);
    exit;
}
 
// Kontroll llogari aktive
if (!$user['is_active']) {
    echo json_encode(['success' => false, 'message' => 'Llogaria juaj eshte e çaktivizuar.']);
    exit;
}
 
// Krijo sesion
session_regenerate_id(true);
$_SESSION['user_id']   = $user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email']     = $user['email'];
$_SESSION['role']      = $user['role'];
 
$redirect = $user['role'] === 'admin'
    ? SITE_URL . '/admin.php'
    : SITE_URL . '/dashboard.php';
 
echo json_encode([
    'success'  => true,
    'message'  => 'Hyrja u krye me sukses!',
    'role'     => $user['role'],
    'redirect' => $redirect
]);
 
 ?>
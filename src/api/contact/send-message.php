<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validim
if (empty($name) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Emri dhe mesazhi jane te detyrueshme.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email i pavlefshem.']);
    exit;
}

// Ruaj ne DB
$stmt = $pdo->prepare(
    'INSERT INTO contact_messages (name, email, subject, message)
     VALUES (?, ?, ?, ?)'
);
$stmt->execute([$name, $email, $subject, $message]);

// Email te adminit
$body = emailTemplate('Mesazh i ri nga kontakti', "
    <p><strong>Nga:</strong> {$name} &lt;{$email}&gt;</p>
    <p><strong>Subjekti:</strong> {$subject}</p>
    <hr>
    <p>{$message}</p>
");
sendEmail(SMTP_USER, 'PostaWeb - Mesazh i ri: ' . $subject, $body);

// Email konfirmimi te perdoruesit
$bodyUser = emailTemplate('Mesazhi juaj u mor!', "
    <p>Pershendetje <strong>{$name}</strong>,</p>
    <p>Mesazhi juaj u mor me sukses. Do t'ju pergjigjemi se shpejti.</p>
    <p><strong>Subjekti:</strong> {$subject}</p>
");
sendEmail($email, 'PostaWeb - Konfirmim mesazhi', $bodyUser);

echo json_encode([
    'success' => true,
    'message' => 'Mesazhi u dergua me sukses!'
]);
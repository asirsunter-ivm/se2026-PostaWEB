<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/crypto.php';

requireLogin();

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

$pkgId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('
    SELECT p.*, u.full_name AS sender_full_name, u.email AS sender_email,
           rc.name AS receiver_city, rcn.name AS receiver_country,
           sc.name AS sender_city, scn.name AS sender_country,
           pt.type_name
    FROM packages p
    INNER JOIN users u           ON u.id = p.sender_id
    INNER JOIN cities rc         ON rc.id = p.receiver_city_id
    INNER JOIN countries rcn     ON rcn.id = rc.country_id
    INNER JOIN package_types pt  ON pt.id = p.package_type_id
    LEFT JOIN cities sc          ON sc.id = u.city_id
    LEFT JOIN countries scn      ON scn.id = sc.country_id
    WHERE p.id = ? AND (p.sender_id = ? OR ? = "admin")
');
$stmt->execute([$pkgId, $_SESSION['user_id'], $_SESSION['role']]);
$pkg = $stmt->fetch();

if (!$pkg) {
    http_response_code(404);
    exit('Pakoja nuk u gjet.');
}

$receiverAddress = decryptData($pkg['receiver_address_encrypted']);
$receiverPhone = $pkg['receiver_phone_encrypted'] ? decryptData($pkg['receiver_phone_encrypted']) : '';

$labelDir = __DIR__ . '/../../labels';
if (!is_dir($labelDir)) mkdir($labelDir, 0755, true);

// Generate QR
$qrPath = $labelDir . '/qr_' . $pkg['tracking_code'] . '.png';
$result = Builder::create()
    ->writer(new PngWriter())
    ->data($pkg['tracking_code'])
    ->size(200)
    ->margin(10)
    ->build();
$result->saveToFile($qrPath);

// PDF
$pdf = new TCPDF('L', 'mm', [150, 100], true, 'UTF-8');
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 8, 'POSTAWEB - SHIPPING LABEL', 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(85, 5, 'FROM:', 0, 1);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(85, 4,
    $pkg['sender_full_name'] . "\n" .
    ($pkg['sender_city'] ?: '-') . ', ' . ($pkg['sender_country'] ?: '-'),
    1, 'L');

$pdf->Image($qrPath, 100, 50, 40, 40);

$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(140, 5, 'TO:', 0, 1);
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(140, 5,
    $pkg['receiver_name'] . "\n" .
    $receiverAddress . "\n" .
    $pkg['receiver_city'] . ', ' . $pkg['receiver_country'] .
    ($receiverPhone ? "\nTel: " . $receiverPhone : ''),
    1, 'L');

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 10, $pkg['tracking_code'], 0, 1, 'C');

$pdf->SetFont('helvetica', '', 7);
$pdf->Cell(0, 4, 'Type: ' . $pkg['type_name'] . ' | Weight: ' . $pkg['weight_kg'] . ' kg | Cost: $' . $pkg['shipping_cost'], 0, 1, 'C');

$pdfPath = $labelDir . '/label_' . $pkg['tracking_code'] . '.pdf';
$pdf->Output($pdfPath, 'F');

$stmt = $pdo->prepare('UPDATE packages SET label_path = ? WHERE id = ?');
$stmt->execute(['labels/label_' . $pkg['tracking_code'] . '.pdf', $pkgId]);

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="label_' . $pkg['tracking_code'] . '.pdf"');
header('Content-Length: ' . filesize($pdfPath));
readfile($pdfPath);
exit;

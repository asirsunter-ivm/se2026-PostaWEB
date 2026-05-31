<?php
/**
 * PostaWeb - Funksione te pergjithshme
 */

function generateTrackingCode() {
    return 'PW' . date('ymd') . strtoupper(bin2hex(random_bytes(4)));
}

function calculateShippingCost($weight_kg, $package_type_id, $sender_country_id, $receiver_country_id, $pdo) {
    $stmt = $pdo->prepare('SELECT base_price FROM package_types WHERE id = ?');
    $stmt->execute([$package_type_id]);
    $type = $stmt->fetch();
    if (!$type) return 0;
    $cost = (float)$type['base_price'];
    $cost += $weight_kg * 1.50;
    if ($sender_country_id != $receiver_country_id) $cost += 10;
    return round($cost, 2);
}

function escape($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($datetime) {
    if (!$datetime) return '-';
    return date('d/m/Y H:i', strtotime($datetime));
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function statusToAlbanian($status) {
    $map = [
        'created'          => 'Krijuar',
        'picked_up'        => 'I marre',
        'in_transit'       => 'Ne tranzit',
        'out_for_delivery' => 'Ne dorezim',
        'delivered'        => 'Dorezuar',
        'returned'         => 'Kthyer',
        'cancelled'        => 'Anuluar'
    ];
    return $map[$status] ?? $status;
}

function statusColor($status) {
    $colors = [
        'created'          => '#fef3c7',
        'picked_up'        => '#dbeafe',
        'in_transit'       => '#ede9fe',
        'out_for_delivery' => '#ffedd5',
        'delivered'        => '#d1fae5',
        'returned'         => '#fee2e2',
        'cancelled'        => '#f3f4f6'
    ];
    return $colors[$status] ?? '#f3f4f6';
}
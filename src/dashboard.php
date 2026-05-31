<?php
require_once __DIR__ . '/includes/referer_check.php';
checkReferer();
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header('Location: admin.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT COUNT(*) FROM packages WHERE sender_id = ?');
$stmt->execute([$userId]);
$total = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM packages WHERE sender_id = ? AND current_status = "delivered"');
$stmt->execute([$userId]);
$delivered = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM packages WHERE sender_id = ? AND current_status IN ("picked_up","in_transit","out_for_delivery")');
$stmt->execute([$userId]);
$inTransit = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT IFNULL(SUM(shipping_cost),0) FROM packages WHERE sender_id = ? AND payment_status = "paid"');
$stmt->execute([$userId]);
$spent = $stmt->fetchColumn();

require_once __DIR__ . '/includes/header.php';
?>

<div class="dashboard-container">
    <h1 style="margin-top: 40px;">Mireserdhe, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">PAKO</div>
            <div class="stat-value"><?= $total ?></div>
            <div class="stat-label">Pako Totale</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">OK</div>
            <div class="stat-value"><?= $delivered ?></div>
            <div class="stat-label">Dorezuar</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">RR</div>
            <div class="stat-value"><?= $inTransit ?></div>
            <div class="stat-label">Ne Tranzit</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">$</div>
            <div class="stat-value">$<?= number_format($spent, 2) ?></div>
            <div class="stat-label">Shpenzuar</div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="send-package.php" class="btn-primary">Dergo Pako te Re</a>
    </div>

    <div class="search-bar">
        <input type="text" id="searchPackages" placeholder="Kerko sipas kodit ose emrit...">
        <select id="filterStatus">
            <option value="">Te gjitha</option>
            <option value="created">Krijuar</option>
            <option value="picked_up">I marre</option>
            <option value="in_transit">Ne tranzit</option>
            <option value="out_for_delivery">Ne dorezim</option>
            <option value="delivered">Dorezuar</option>
            <option value="returned">Kthyer</option>
            <option value="cancelled">Anuluar</option>
        </select>
    </div>

    <div class="table-container">
        <h2>Pakot e Mia</h2>
        <table class="packages-table">
            <thead>
                <tr>
                    <th>Kodi</th>
                    <th>Destinacioni</th>
                    <th>Tipi</th>
                    <th>Pesha</th>
                    <th>Kosto</th>
                    <th>Statusi</th>
                    <th>Pagesa</th>
                    <th>Data</th>
                    <th>Veprime</th>
                </tr>
            </thead>
            <tbody id="packagesTableBody">
                <tr><td colspan="9" style="text-align:center;">Duke ngarkuar...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    loadPackages();
    $('#searchPackages, #filterStatus').on('input change', loadPackages);

    function loadPackages() {
        var q = $('#searchPackages').val();
        var status = $('#filterStatus').val();
        $.getJSON('api/packages/list.php', { q: q, status: status }, function(res) {
            if (!res.success || res.packages.length === 0) {
                $('#packagesTableBody').html('<tr><td colspan="9" style="text-align:center;">Asnje pako nuk u gjet.</td></tr>');
                return;
            }
            var html = '';
            res.packages.forEach(function(p) {
                var paymentBadge = p.payment_status === 'paid'
                    ? '<span style="background:#d1fae5;color:#065f46;padding:3px 8px;border-radius:4px;font-size:12px;">Paguar</span>'
                    : '<span style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:4px;font-size:12px;">Ne pritje</span>';
                html += '<tr><td><strong>' + p.tracking_code + '</strong></td><td>' + p.receiver_name + '</td><td>' + p.package_type + '</td><td>' + p.weight_kg + ' kg</td><td>$' + p.shipping_cost + '</td><td><span style="background:#dbeafe;color:#1e40af;padding:3px 8px;border-radius:4px;font-size:12px;">' + p.status_label + '</span></td><td>' + paymentBadge + '</td><td>' + p.created_at + '</td><td>' + (p.payment_status === 'paid' ? '<a href="api/label/generate.php?id=' + p.id + '" target="_blank">Etiketa</a>' : '<a href="send-package.php?id=' + p.id + '">Paguaj</a>') + '</td></tr>';
            });
            $('#packagesTableBody').html(html);
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


<?php
require_once __DIR__ . '/includes/referer_check.php';
checkReferer();
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Statistika globale
$totalUsers     = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalPackages  = $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn();
$totalRevenue   = $pdo->query('SELECT IFNULL(SUM(shipping_cost),0) FROM packages WHERE payment_status = "paid"')->fetchColumn();
$pendingPackages= $pdo->query('SELECT COUNT(*) FROM packages WHERE current_status IN ("created","picked_up","in_transit","out_for_delivery")')->fetchColumn();
$unreadMessages = $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn();

require_once __DIR__ . '/includes/header.php';
?>

<div class="dashboard-container">
    <h1 style="margin-top: 30px;">Admin Panel</h1>
    <p style="color:#666;">Mireserdhe, <?= htmlspecialchars($_SESSION['full_name']) ?></p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">USERS</div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Perdorues</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">PAKO</div>
            <div class="stat-value"><?= $totalPackages ?></div>
            <div class="stat-label">Pako Totale</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">PEN</div>
            <div class="stat-value"><?= $pendingPackages ?></div>
            <div class="stat-label">Aktive</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">$</div>
            <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
            <div class="stat-label">Te Ardhura</div>
        </div>
    </div>

    <div class="admin-tabs">
        <button class="tab-btn active" data-tab="packages">Pako</button>
        <button class="tab-btn" data-tab="users">Perdorues</button>
        <button class="tab-btn" data-tab="messages">Mesazhet (<?= $unreadMessages ?>)</button>
    </div>

    <div class="tab-content active" id="tab-packages">
        <div class="search-bar">
            <input type="text" id="searchPackages" placeholder="Kerko sipas kodit...">
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
            <table class="packages-table">
                <thead>
                    <tr>
                        <th>Kodi</th>
                        <th>Dergues</th>
                        <th>Destinacioni</th>
                        <th>Tipi</th>
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

    <div class="tab-content" id="tab-users">
        <div class="table-container">
            <h2>Perdoruesit</h2>
            <table class="packages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Emri</th>
                        <th>Email</th>
                        <th>Roli</th>
                        <th>Statusi</th>
                        <th>Data Regjistrimit</th>
                        <th>Veprime</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr><td colspan="7" style="text-align:center;">Duke ngarkuar...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-content" id="tab-messages">
        <div class="table-container">
            <h2>Mesazhet e Kontaktit</h2>
            <table class="packages-table">
                <thead>
                    <tr>
                        <th>Nga</th>
                        <th>Email</th>
                        <th>Subjekti</th>
                        <th>Mesazhi</th>
                        <th>Data</th>
                        <th>Statusi</th>
                    </tr>
                </thead>
                <tbody id="messagesTableBody">
                    <tr><td colspan="6" style="text-align:center;">Duke ngarkuar...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- STATUS UPDATE MODAL -->
<div id="statusModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h2>Perditeso Statusin</h2>
        <input type="hidden" id="updatePackageId">
        <div class="form-group">
            <label>Statusi i ri</label>
            <select id="newStatus">
		<option value="paid">Paguar</option>
                <option value="picked_up">I marr</option>
                <option value="in_transit">Ne tranzit</option>
                <option value="out_for_delivery">Ne dorezim</option>
                <option value="delivered">Dorezuar</option>
                <option value="returned">Kthyer</option>
                <option value="cancelled">Anuluar</option>
            </select>
		    
        </div>
        <div class="form-group">
            <label>Vendndodhja</label>
            <input type="text" id="updateLocation" placeholder="P.sh. Tirane - Hub">
        </div>
        <div class="form-group">
            <label>Shenime</label>
            <textarea id="updateNote" rows="3"></textarea>
        </div>
        <button class="btn-primary" id="saveStatusBtn">Ruaj</button>
    </div>
</div>

<script>
$(document).ready(function() {
    loadPackages();
    loadUsers();
    loadMessages();

    $('.tab-btn').on('click', function() {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').removeClass('active');
        $('#tab-' + $(this).data('tab')).addClass('active');
    });

    $('#searchPackages, #filterStatus').on('input change', loadPackages);

    function loadPackages() {
        var q = $('#searchPackages').val();
        var status = $('#filterStatus').val();
        $.getJSON('api/packages/list.php', { q: q, status: status }, function(res) {
            if (!res.success || res.packages.length === 0) {
                $('#packagesTableBody').html('<tr><td colspan="9" style="text-align:center;">Asnje pako.</td></tr>');
                return;
            }
            var html = '';
            res.packages.forEach(function(p) {
                var paymentBadge = p.payment_status === 'paid' ? 'Paguar' : 'Ne pritje';
                html += '<tr>' +
                    '<td><strong>' + p.tracking_code + '</strong></td>' +
                    '<td>' + (p.sender_name || '-') + '</td>' +
                    '<td>' + p.receiver_name + '</td>' +
                    '<td>' + p.package_type + '</td>' +
                    '<td>$' + p.shipping_cost + '</td>' +
                    '<td>' + p.status_label + '</td>' +
                    '<td>' + paymentBadge + '</td>' +
                    '<td>' + p.created_at + '</td>' +
                    '<td><button class="btn-update" data-id="' + p.id + '">Perditeso</button></td>' +
                    '</tr>';
            });
            $('#packagesTableBody').html(html);
        });
    }

    function loadUsers() {
        $.getJSON('api/admin/users-list.php', function(res) {
            if (!res.success) {
                $('#usersTableBody').html('<tr><td colspan="7" style="text-align:center;">Gabim.</td></tr>');
                return;
            }
            var html = '';
            res.users.forEach(function(u) {
                html += '<tr>' +
                    '<td>' + u.id + '</td>' +
                    '<td>' + u.full_name + '</td>' +
                    '<td>' + u.email + '</td>' +
                    '<td>' + u.role + '</td>' +
                    '<td>' + (u.is_active == 1 ? 'Aktiv' : 'Joaktiv') + '</td>' +
                    '<td>' + u.created_at + '</td>' +
                    '<td><button class="btn-delete-user" data-id="' + u.id + '">Fshi</button></td>' +
                    '</tr>';
            });
            $('#usersTableBody').html(html);
        });
    }

    function loadMessages() {
        $.getJSON('api/admin/messages-list.php', function(res) {
            if (!res.success || res.messages.length === 0) {
                $('#messagesTableBody').html('<tr><td colspan="6" style="text-align:center;">Asnje mesazh.</td></tr>');
                return;
            }
            var html = '';
           $('#messagesTableBody').empty();
res.messages.forEach(function(m) {
    var $tr = $('<tr></tr>');
    $tr.append($('<td></td>').text(m.name));
    $tr.append($('<td></td>').text(m.email));
    $tr.append($('<td></td>').text(m.subject || '-'));
    $tr.append($('<td></td>').text(m.message.substring(0, 100) + '...'));
    $tr.append($('<td></td>').text(m.created_at));
    $tr.append($('<td></td>').text(m.is_read == 1 ? 'Lexuar' : 'I ri'));
    $('#messagesTableBody').append($tr);
});
        });
    }

    $(document).on('click', '.btn-update', function() {
        $('#updatePackageId').val($(this).data('id'));
        $('#statusModal').fadeIn();
    });

    $('#saveStatusBtn').on('click', function() {
    var data = {
        package_id: $('#updatePackageId').val(),
        status: $('#newStatus').val(),
        location: $('#updateLocation').val(),
        note: $('#updateNote').val(),
    };
        $.post('api/packages/update-status.php', data, function(res) {
            if (res.success) {
                alert('Statusi u perditesua!');
                $('#statusModal').fadeOut();
                loadPackages();
            } else {
                alert('Gabim: ' + res.message);
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete-user', function() {
        if (!confirm('Jeni i sigurt?')) return;
        $.post('api/admin/user-delete.php', { id: $(this).data('id') }, function(res) {
            if (res.success) loadUsers();
            else alert('Gabim: ' + res.message);
        }, 'json');
    });
});
</script>

<style>
.admin-tabs { display: flex; gap: 10px; margin: 30px 0 20px; border-bottom: 2px solid #eee; }
.tab-btn { padding: 12px 25px; background: none; border: none; cursor: pointer; font-size: 15px; font-weight: 500; color: #666; border-bottom: 3px solid transparent; }
.tab-btn.active { color: #1F4E79; border-bottom-color: #1F4E79; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.btn-update, .btn-delete-user { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
.btn-update { background: #1F4E79; color: white; }
.btn-delete-user { background: #dc3545; color: white; }
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


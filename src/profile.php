<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/crypto.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT full_name, email, phone_encrypted FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
$phone = $user['phone_encrypted'] ? decryptData($user['phone_encrypted']) : '';

require_once __DIR__ . '/includes/header.php';
?>

<div class="profile-container">
    <h1>Profili Im</h1>

    <div class="profile-grid">
        <div class="profile-card">
            <h2>Te Dhenat Personale</h2>
            <form id="profileForm">
                <div class="form-group">
                    <label>Emri i Plote</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email (nuk mund te ndryshohet)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Telefoni</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
                </div>
                <p id="profileMsg" class="msg" style="display:none;"></p>
                <button type="submit" class="btn-primary">Ruaj Ndryshimet</button>
            </form>
        </div>

        <div class="profile-card">
            <h2>Ndrysho Fjalekalimin</h2>
            <form id="passwordForm">
                <div class="form-group">
                    <label>Fjalekalimi Aktual</label>
                   <div class="password-wrapper">
    <input type="password" name="old_password" required>
    <i class="fa-solid fa-eye toggle-password"></i>
</div>
                </div>
                <div class="form-group">
                    <label>Fjalekalimi i Ri (min 8 karaktere)</label>
                    <div class="password-wrapper">
    <input type="password" name="new_password" required minlength="8">
    <i class="fa-solid fa-eye toggle-password"></i>
</div>
                </div>
                <div class="form-group">
                    <label>Konfirmo Fjalekalimin</label>
                   <div class="password-wrapper">
    <input type="password" name="confirm_password" required minlength="8">
    <i class="fa-solid fa-eye toggle-password"></i>
</div>
                </div>
                <p id="passwordMsg" class="msg" style="display:none;"></p>
                <button type="submit" class="btn-primary">Ndrysho Fjalekalimin</button>
            </form>
        </div>
    </div>
</div>

<script>
$('.toggle-password').on('click', function() {
    var $input = $(this).siblings('input');
    if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        $input.attr('type', 'password');
        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
$('#profileForm').on('submit', function(e) {
    e.preventDefault();
    $.post('api/auth/update-profile.php', $(this).serialize(), function(res) {
        $('#profileMsg').text(res.message)
            .removeClass('error-msg success-msg')
            .addClass(res.success ? 'success-msg' : 'error-msg')
            .show();
    }, 'json');
});

$('#passwordForm').on('submit', function(e) {
    e.preventDefault();
    var newPass = $(this).find('[name=new_password]').val();
    var confirmPass = $(this).find('[name=confirm_password]').val();
    if (newPass !== confirmPass) {
        $('#passwordMsg').text('Fjalekalimet nuk perputhen!')
            .removeClass('success-msg').addClass('error-msg').show();
        return;
    }
    $.post('api/auth/change-password.php', $(this).serialize(), function(res) {
        $('#passwordMsg').text(res.message)
            .removeClass('error-msg success-msg')
            .addClass(res.success ? 'success-msg' : 'error-msg')
            .show();
        if (res.success) $('#passwordForm')[0].reset();
    }, 'json');
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
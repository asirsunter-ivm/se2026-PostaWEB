<?php
require_once __DIR__ . '/includes/referer_check.php';
checkReferer();
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';

// Vetem kliente te loguar
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Merr qytetet dhe tipet e pakos per dropdown
$cities       = $pdo->query('SELECT c.id, c.name, co.name AS country FROM cities c INNER JOIN countries co ON co.id = c.country_id ORDER BY co.name, c.name')->fetchAll();
$packageTypes = $pdo->query('SELECT id, type_name, base_price FROM package_types ORDER BY base_price')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="send-package-container">
    <h1>Dergoni pakon tuaj</h1>
    <p class="subtitle">Plotesoni te dhenat dhe paguani per te krijuar pakon</p>

    <!-- STEPS INDICATOR -->
    <div class="steps-indicator">
        <div class="step-bar active" data-step="1"><span>1</span> Dergues</div>
        <div class="step-bar" data-step="2"><span>2</span> Destinacion</div>
        <div class="step-bar" data-step="3"><span>3</span> Pakoja</div>
        <div class="step-bar" data-step="4"><span>4</span> Pagese</div>
    </div>

    <form id="packageForm">

        <!-- STEP 1: SENDER -->
        <div class="form-step active" data-step="1">
            <h2>1. Te Dhenat e Derguesit</h2>
            <div class="form-group">
                <label>Emri i plote *</label>
                <input type="text" name="sender_name" value="<?= htmlspecialchars($_SESSION['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Adresa *</label>
                <input type="text" name="sender_address" placeholder="Rruga, Nr." required>
            </div>
            <div class="form-group">
                <label>Qyteti *</label>
                <select name="sender_city_id" required>
                    <option value="">-- Zgjidh qytetin --</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?>, <?= htmlspecialchars($c['country']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-next">Vazhdo</button>
            </div>
        </div>

        <!-- STEP 2: RECEIVER -->
        <div class="form-step" data-step="2">
            <h2>2. Te Dhenat e Destinacionit</h2>
            <div class="form-group">
                <label>Emri i plote *</label>
                <input type="text" name="receiver_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="receiver_email" placeholder="(opsional - per njoftim)">
            </div>
            <div class="form-group">
                <label>Telefoni</label>
                <input type="text" name="receiver_phone">
            </div>
            <div class="form-group">
                <label>Adresa *</label>
                <input type="text" name="receiver_address" required>
            </div>
            <div class="form-group">
                <label>Qyteti *</label>
                <select name="receiver_city_id" required>
                    <option value="">-- Zgjidh qytetin --</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?>, <?= htmlspecialchars($c['country']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-prev">Kthehu pas</button>
                <button type="button" class="btn-next">Vazhdo</button>
            </div>
        </div>

        <!-- STEP 3: PACKAGE DETAILS -->
        <div class="form-step" data-step="3">
            <h2>3. Detajet e Pakos</h2>
            <div class="form-group">
                <label>Tipi i pakos *</label>
                <select name="package_type_id" id="packageType" required>
                    <option value="">-- Zgjidh tipin --</option>
                    <?php foreach ($packageTypes as $t): ?>
                        <option value="<?= $t['id'] ?>" data-price="<?= $t['base_price'] ?>">
                            <?= htmlspecialchars($t['type_name']) ?> ($<?= $t['base_price'] ?> baze)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pesha (kg) *</label>
                <input type="number" name="weight_kg" id="weightKg" step="0.1" min="0.1" required>
            </div>
            <div class="form-group">
                <label>Vlera e deklaruar ($)</label>
                <input type="number" name="declared_value" step="0.01" min="0" value="0">
            </div>
            <div class="form-group">
                <label>Pershkrim</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-prev">Kthehu pas</button>
                <button type="button" class="btn-next">Vazhdo</button>
            </div>
        </div>

        <!-- STEP 4: SUMMARY + PAYMENT -->
        <div class="form-step" data-step="4">
            <h2>4. Permbledhje dhe Pagese</h2>

            <div id="summaryBox" class="summary-box"></div>

            <div id="paymentSection" style="display:none;">
                <h3>Paguaj me PayPal</h3>
                <div id="paypal-button-container"></div>
                <p id="paymentMsg" class="success-msg" style="display:none;"></p>
            </div>

            <div class="form-actions" id="createActions">
                <button type="button" class="btn-prev">Kthehu pas</button>
                <button type="button" id="createPackageBtn" class="btn-primary">Krijo Pakon</button>
            </div>
        </div>

    </form>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT_ID ?>&currency=USD"></script>
<script src="assets/js/send-package.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

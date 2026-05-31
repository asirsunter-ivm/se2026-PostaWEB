<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>PostaWeb</h3>
           <p>Ne jemi nje platforme dixhitale e dedikuar per te thjeshtuar menaxhimin dhe ndjekjen e pakove tuaja. I frymezuar nga modelet me te mira globale si 'Parcel', website-i jone bashkon sherbimet e koriereve te ndryshem ne nje vend te vetem. Misioni jone eshte t'ju ofrojme nje eksperience te shpejte, te sigurt dhe ne kohe reale, duke bere qe kontrolli i dergesave tuaja te jete vetem nje klikim larg.</p>
        </div>
        <div class="footer-section">
            <h4>Akses i Lehte</h4>
            <ul>
                <li><a href="index.php#hero">Home</a></li>
                <li><a href="index.php#about">About Us</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Kontakte</h4>
            <p>postaweb.finiteloop@gmail.com</p>
	    <p>+355 69 234 5678</p>
            <p>Tirane, Shqiperi</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> PostaWeb - Finite Loop Team</p>
    </div>
</footer>

<div id="loginModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h2>Hyr ne PostaWeb</h2>
        <form id="loginForm">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
    <label>Fjalekalimi</label>
    <div class="password-wrapper">
        <input type="password" name="password" required>
        <i class="fa-solid fa-eye toggle-password"></i>
    </div>
</div>
            <p id="loginError" class="error-msg" style="display:none;"></p>
            <button type="submit" class="btn-primary">Hyr</button>
        </form>
        <p class="modal-footer">
            Nuk keni llogari? <a href="#" id="showRegister">Krijo Llogari</a>
        </p>
    </div>
</div>

<div id="registerModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h2>Krijo Llogari</h2>
        <form id="registerForm">
            <div class="form-group">
                <label>Emri i Plote</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Telefoni</label>
                <input type="text" name="phone">
            </div>
            <div class="form-group">
    <label>Fjalekalimi (min 8 karaktere)</label>
    <div class="password-wrapper">
        <input type="password" name="password" required minlength="8">
        <i class="fa-solid fa-eye toggle-password"></i>
    </div>
</div>
            <p id="registerError" class="error-msg" style="display:none;"></p>
            <button type="submit" class="btn-primary">Regjistrohu</button>
        </form>
        <p class="modal-footer">
            Keni llogari? <a href="#" id="showLogin">Hyr</a>
        </p>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>

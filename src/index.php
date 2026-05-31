<?php require_once __DIR__ . '/includes/header.php'; ?>

<section class="hero" id="hero">

    <video autoplay muted loop playsinline class="hero-video">
        <source src="https://cdn.coverr.co/videos/coverr-delivery-service-1560125678164?download=1080p" type="video/mp4">
    </video>

    <div class="hero-overlay"></div>

    <div class="hero-content">

        <h1>Fast & Secure Delivery</h1>

        <p class="hero-subtitle">
            Modern postal and package tracking platform
        </p>

       
        <form id="trackForm" class="track-form">

            <div class="form-group">
               
               <input type="text" name="tracking_code" placeholder="p.sh. PW123456" required oninvalid="this.setCustomValidity('Ju lutem vendosni kodin e pakos tuaj.')" oninput="this.setCustomValidity('')">
            </div>

            <button type="submit" class="btn-primary">
                Gjurmo Pakon
            </button>

        </form>
	<div id="trackResult" style="display:none; margin-top:30px; background:white; 	border-radius:12px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.1); text-	align:left; color:#333; max-width:800px; margin-left:auto; margin-right:auto;">	</div>
        
	<div id="trackingProgress" class="tracking-progress" style="display:none !important; margin-top:30px;">

            <div class="progress-step">
                <div class="circle">1</div>
                <p>Paketa u krijua</p>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step">
                <div class="circle">2</div>
                <p>Ne Aeroport</p>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step">
                <div class="circle">3</div>
                <p>Ne Dogane</p>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step">
                <div class="circle">4</div>
                <p>Ne Poste</p>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step">
                <div class="circle">5</div>
                <p>Korrieri po vjen</p>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step">
                <div class="circle">6</div>
                <p>Dorezuar</p>
            </div>

        </div>

    </div>

</section>


<section id="about" class="about-section">
    <div class="container">
        <h2>Rreth Nesh</h2>
        <p class="section-subtitle">PostaWeb - Sherbimi yt postar dixhital</p>

        <div class="about-grid">
            <div class="about-card">
                <div class="icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Dergim i Shpejte</h3>
                <p>Dergo pako brenda dhe jashte vendit me kosto te ulet.</p>
            </div>
            <div class="about-card">
                <div class="icon"><i class="fa-solid fa-magnifying-glass-location"></i></div>
                <h3>Gjurmim ne Kohe Reale</h3>
                <p>Ndiq statusin e pakos tuaj ne çdo moment me kodin unik te gjurmimit.</p>
            </div>
            <div class="about-card">
                <div class="icon"><i class="fa-solid fa-credit-card"></i></div>
                <h3>Pagese Online</h3>
                <p>Paguaj sigurte me PayPal direkt nga platforma jone.</p>
            </div>
            <div class="about-card">
                <div class="icon"><i class="fa-solid fa-bell"></i></div>
                <h3>Njoftime Automatike</h3>
                <p>Merr email per cdo ndryshim statusi te pakos suaj.</p>
            </div>
        </div>
    </div>
</section>




<section id="contact" class="contact-section">
    <div class="container">
        <h2>Na Kontaktoni</h2>
        <p class="section-subtitle">Lini nje mesazh ose nje vleresim per ne</p>

        <div class="contact-grid">

            <form id="contactForm" class="contact-form">
                <h3>Dergo Mesazh</h3>

                <div class="form-group">
                    <label>Emri</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Subjekti</label>
                    <input type="text" name="subject">
                </div>

                <div class="form-group">
                    <label>Mesazhi</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>

                <p id="contactMsg" class="success-msg" style="display:none;"></p>

                <button type="submit" class="btn-primary">
                    Dergo Mesazh
                </button>

            </form>

            <form id="reviewForm" class="contact-form">
                <h3>Lini nje Vleresim</h3>

                <div class="form-group">
                    <label>Emri</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Vleresimi</label>

                    <div class="stars">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>

                    <input type="hidden" id="ratingValue" name="rating" value="0">
                </div>

                <div class="form-group">
                    <label>Komentet</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>

                <p id="reviewMsg" class="success-msg" style="display:none;"></p>

                <button type="submit" class="btn-primary">
                    Dergo Vleresimin
                </button>

            </form>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
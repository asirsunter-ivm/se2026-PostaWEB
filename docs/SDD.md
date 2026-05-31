# Software Design Document (SDD)
## PostaWeb - Platforma e Sherbimit Postar

**Ekipi:** Finite Loop  
**Versioni:** v1.0  
**Data:** 20 Shkurt 2025  

---

## 1. Arkitektura e Sistemit

PostaWeb ndjek modelin **3-Tier Architecture**:

| Shtresa | Teknologjia | Pershkrimi |
|---------|-------------|------------|
| Presentation Layer | HTML5, CSS3, JavaScript, jQuery, AJAX | Nderfaqja grafike |
| Application Layer | PHP 8.0+ | Logjika e biznesit dhe API endpoints |
| Data Layer | MySQL 5.7+ (PDO) | Ruajtja e strukturuar e te dhenave |

---

## 2. Komponentet Kryesore

| Komponenti | Skedaret | Pershkrimi |
|-----------|----------|------------|
| Authentication | api/auth/ | Login, register, logout, session, change-password |
| Package Management | api/packages/ | CRUD pakove, tracking, update-status |
| Payment | api/payment/ | PayPal Sandbox (create-order, capture-order) |
| PDF Label | api/label/generate.php | Gjenerim PDF me TCPDF dhe QR Code |
| Email | includes/mailer.php | PHPMailer + Gmail SMTP |
| Contact & Reviews | api/contact/ | Forma kontakti dhe vleresimeve |
| Admin Panel | api/admin/ | Menaxhim perdoruesve dhe mesazheve |
| Security | includes/session.php, crypto.php | Access control, AES-256 encryption |

---

## 3. Rrjedha e Kerkesave (Request Flow)
Browser (jQuery)
→ AJAX POST/GET
→ PHP (session check + referer check)
→ PDO Prepared Statement
→ MySQL
→ JSON Response
→ DOM Update (pa rifreskuar faqen)

---

## 4. Dizajni i Databazës

**Databaza:** postawebIS | **Normalizimi:** 3NF | **Tabela:** 10

### Marredheniet kryesore:
- users (1) → packages (N)
- packages (1) → tracking_history (N)
- packages (N) → package_types (1)
- packages (N) → countries (1)
- packages (N) → cities (1)
- users (1) → notifications (N)
- users (1) → reviews (N)
- users (1) → contact_messages (N)

Detajet e plota te tabelave: [dbschema.md](dbschema.md)

---

## 5. Siguria

| Masa | Implementimi |
|------|-------------|
| SQL Injection | PDO Prepared Statements |
| XSS | .text() ne jQuery, htmlspecialchars() ne PHP |
| CSRF | Referer header check |
| Password | bcrypt (password_hash/verify) |
| Data Encryption | AES-256-CBC per telefon dhe adresa |
| Access Control | requireLogin() / requireAdmin() |
| File Upload | .htaccess bllokon PHP execution ne uploads/ |
| Session | session_regenerate_id() pas login |

---

## 6. Algoritmet Kryesore

### Gjenerimi i Kodit Tracking
PW + YYYYMMDD + 6_karaktere_hex
Shembull: PW20250220A3F2B1

### Llogaritja e Kostos
Kosto = (Pesha_kg x base_price) x shipping_multiplier

### Enkriptimi AES-256
IV = openssl_random_pseudo_bytes(16)
Ciphertext = openssl_encrypt(data, AES-256-CBC, KEY, 0, IV)
Ruhet: base64_encode(IV + Ciphertext)

---

## 7. Varësitë Externe

| Libraria | Versioni | Qellimi |
|----------|---------|---------|
| PHPMailer | ^6.8 | Dergim email SMTP |
| TCPDF | ^6.6 | Gjenerim PDF |
| Endroid QrCode | ^4.8 | Gjenerim QR Code |
| PayPal REST API | v2 | Procesim pagesash |

---

## 8. Struktura e Skedareve
src/
├── index.php          # Faqja kryesore
├── dashboard.php      # Dashboard klienti
├── profile.php        # Profili klientit
├── admin.php          # Panel administratori
├── send-package.php   # Dergim pakoje (4-step wizard)
├── api/
│   ├── auth/          # Autentikimi
│   ├── packages/      # Menaxhimi pakove
│   ├── payment/       # PayPal
│   ├── label/         # PDF gjenerim
│   ├── admin/         # Admin endpoints
│   └── contact/       # Kontakt dhe vleresime
├── includes/
│   ├── db.php         # Lidhja PDO
│   ├── config.php     # Konfigurimi (private)
│   ├── session.php    # Access control
│   ├── mailer.php     # Email funksione
│   └── crypto.php     # AES-256 funksione
└── assets/
├── css/style.css  # Stilizimi
└── js/            # JavaScript/jQuery

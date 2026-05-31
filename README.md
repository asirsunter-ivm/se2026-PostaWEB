# PostaWeb – Platforma e Sherbimit Postar

Platformë web për menaxhimin e shërbimeve postare, ndërtuar nga ekipi **Finite Loop**.

---

## Ekipi

| Emri | Roli |
|------|------|
| Asir Sunter | Project Lead / Backend (Auth, DB, PayPal, PDF) |
| Borana Nuzi | Frontend Developer (UI/UX, HTML/CSS, jQuery) |
| Anxhi Gogollari | Backend Developer (Tracking, CRUD, AJAX) |
| Darli Selmanllari | Backend / Testing (Email, File Upload, Siguria) |

**Grupi:** A1

---

## Pershkrimi

PostaWeb eshte nje platforme web qe mundeson menaxhimin e plote te sherbimeve postare ne menyre dixhitale. Platforma ofron:

- Regjistrim dhe autentikim i sigurt me role (klient/administrator)
- Gjurmim porosie ne kohe reale me kod unik tracking
- Dergim pakoje me 4-step wizard dhe gjenerim automatik etikete PDF
- Sistem pagesash online me PayPal Sandbox
- Njoftime automatike me email (PHPMailer + Gmail SMTP)
- Panel administratori me menaxhim te plote CRUD
- Siguri e avancuar (bcrypt, AES-256, PDO, XSS/CSRF protection)

---

## Teknologjite

| Shtresa | Teknologjia |
|---------|-------------|
| Front-End | HTML5, CSS3, JavaScript, jQuery, AJAX |
| Back-End | PHP 8.0+ |
| Databaza | MySQL 5.7+ (PDO, 3NF) |
| Email | PHPMailer + Gmail SMTP |
| Pagesa | PayPal REST API (Sandbox) |
| PDF | TCPDF + Endroid QrCode |
| Siguria | bcrypt, AES-256-CBC, Prepared Statements |

---

## Instalimi Lokal

1. Instalo XAMPP dhe starto Apache + MySQL
2. Klono repository-n:
```bash
git clone https://github.com/asirsunter-ivm/se2026-PostaWEB.git
```
3. Kopjo ne htdocs:
```powershell
Copy-Item -Recurse se2026-PostaWEB C:\xampp\htdocs\
```
4. Instalo varësitë:
```bash
cd src && composer install
```
5. Importo databazën ne phpMyAdmin (`postawebIS`)
6. Konfiguro `src/includes/config.php` me kredencialet lokale
7. Hap `http://localhost/se2026-PostaWEB/src`

---

## Struktura e Projektit
e2026-PostaWEB/
├── docs/           # Dokumentacioni (SRS, SDD, DB Schema, Test Report)
├── src/            # Kodi burimor
│   ├── api/        # Endpoints (auth, packages, payment, label, admin, contact)
│   ├── assets/     # CSS, JS, imazhe
│   ├── includes/   # DB, config, mailer, session, crypto
│   └── *.php       # Faqet kryesore
└── tests/          # Skedaret e testimit

---

## Dokumentacioni

| Dokumenti | Skedari |
|-----------|---------|
| Software Requirements Spec. | [docs/SRS.md](docs/SRS.md) |
| Software Design Document | [docs/SDD.md](docs/SDD.md) |
| Database Schema | [docs/dbschema.md](docs/dbschema.md) |
| Test Report | [docs/test-report.md](docs/test-report.md) |
| Deployment Guide | [docs/deployment.md](docs/deployment.md) |
| User Manual | [docs/user-manual.md](docs/user-manual.md) |

---

## Metodologjia

Projekti u zhvillua sipas **Agile/Scrum** me 4 sprinte dyjavore (20 Shkurt – 16 Prill 2025), te menaxhuara nëpërmjet **GitHub Projects**.

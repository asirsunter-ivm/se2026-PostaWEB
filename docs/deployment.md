# Deployment Guide
## PostaWeb - Platforma e Sherbimit Postar

**Ekipi:** Finite Loop  
**Versioni:** v1.0  
**Data:** 16 Prill 2025  

---

## 1. Kerkesa te Sistemit

| Softueri | Versioni Minimal |
|----------|-----------------|
| PHP | 8.0+ |
| MySQL | 5.7+ |
| Apache | 2.4+ |
| Composer | 2.x |
| Git | 2.x |

---

## 2. Instalimi Lokal (XAMPP)

### Hapi 1: Instalo XAMPP
Shkarko dhe instalo XAMPP nga https://www.apachefriends.org/
Starto **Apache** dhe **MySQL** nga XAMPP Control Panel.

### Hapi 2: Klono Repository-n
```bash
git clone https://github.com/asirsunter-ivm/se2026-PostaWEB.git
```

### Hapi 3: Kopjo ne htdocs
```powershell
Copy-Item -Recurse se2026-PostaWEB C:\xampp\htdocs\
```

### Hapi 4: Instalo Varësitë PHP
```bash
cd C:\xampp\htdocs\se2026-PostaWEB\src
composer install
```

### Hapi 5: Konfiguro Databazën
1. Hap phpMyAdmin: `http://localhost/phpmyadmin`
2. Krijo databaze te re me emrin `postawebIS`
3. Importo skedarin SQL te projektit

### Hapi 6: Konfiguro config.php
Kopjo `src/includes/config.example.php` si `src/includes/config.php` dhe ploteso:

```php
// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'postawebIS');
define('DB_USER', 'root');
define('DB_PASS', '');

// SMTP (Gmail)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'email_juaj@gmail.com');
define('SMTP_PASS', 'app_password_juaj');
define('SMTP_PORT', 587);

// PayPal Sandbox
define('PAYPAL_CLIENT_ID', 'client_id_juaj');
define('PAYPAL_SECRET', 'secret_juaj');
define('PAYPAL_MODE', 'sandbox');

// Site
define('SITE_URL', 'http://localhost/se2026-PostaWEB/src');
```

### Hapi 7: Konfiguro Gmail App Password
1. Hap Google Account → Security
2. Aktivizo 2-Factor Authentication
3. Shko tek App Passwords → Gjenero password te ri
4. Vendos password-in ne SMTP_PASS ne config.php

### Hapi 8: Testo Instalimin
Hap browser dhe shko tek: http://localhost/se2026-PostaWEB/src

---

## 3. Llogaria e Adminit

Pas importimit te databazës, llogaria default e adminit eshte:

| Fusha | Vlera |
|-------|-------|
| Email | admin@postaweb.al |
| Password | Admin@123 |

**KUJDES:** Ndrysho fjalekalimin pas hyrjes se pare!

---

## 4. Struktura e Foldereve te Rendesishme

| Folderi | Pershkrimi |
|---------|------------|
| `src/includes/config.php` | Konfigurimi kryesor (nuk ngarkohet ne GitHub) |
| `src/labels/` | Etiketat PDF te gjeneruara |
| `src/assets/` | CSS, JS, imazhe |
| `docs/` | Dokumentacioni i projektit |

---

## 5. Probleme te Zakonshme

| Problemi | Zgjidhja |
|----------|---------|
| "Connection refused" | Kontrollo nese MySQL eshte startuar ne XAMPP |
| "composer not found" | Instalo Composer nga https://getcomposer.org/ |
| Email nuk dërgohet | Kontrollo SMTP_PASS dhe aktivizo "Less secure apps" ose App Password |
| PDF nuk gjenderohet | Kontrollo nese TCPDF eshte instaluar (composer install) |
| "Not Found" ne browser | Kontrollo nese projekti eshte ne htdocs dhe SITE_URL eshte korrekt |

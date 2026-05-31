# PostaWeb - Platforma e Sherbimit Postar

Projekt nga ekipi **Finite Loop** per lenden Programim ne Web (PW2526).

## Anetaret e Ekipit
- Asir Sunter - Project Lead / Backend
- Borana Nuzi - Frontend Developer
- Anxhi Gogollari - Backend Developer
- Darli Selmanllari - Backend / Testing

## Teknologjite
- Frontend: HTML, CSS, JavaScript, jQuery, AJAX
- Backend: PHP 8.0
- Database: MySQL (3NF)
- Pagese: PayPal REST API
- Email: PHPMailer + Gmail SMTP
- PDF: TCPDF + QR Code

## Modulet
- User Authentication
- Email Integration
- File Upload & Download
- Data Management (CRUD)
- Search Utility
- PayPal Integration (BONUS)

## Instalimi
1. Klono repo ne C:/xampp/htdocs/ProjektiWEB
2. composer install
3. Importo database/postaweb.sql ne phpMyAdmin
4. Konfiguro includes/config.php
5. Start XAMPP
6. Hap http://localhost/ProjektiWEB/

## Llogari Demo
- Admin: admin@postaweb.al / Admin@123
- Klient: klient1@test.al / Test@123

## Siguria
- SQL Injection: PDO Prepared Statements
- XSS: htmlspecialchars()
- Password: bcrypt
- Te dhena sensitive: AES-256-CBC
- Referer check + .htaccess

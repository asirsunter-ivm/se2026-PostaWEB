# Test Plan dhe Test Report
## PostaWeb - Platforma e Sherbimit Postar

**Ekipi:** Finite Loop  
**Versioni:** v1.0  
**Data:** 20 Shkurt 2025  

---

## 1. Qellimi i Testimit
Ky dokument pershkruan planin dhe rezultatet e testimit te platformes PostaWeb. Testimi u krye manualisht nga anetaret e ekipit Finite Loop.

---

## 2. Mjedisi i Testimit

| Elementi | Detaji |
|----------|--------|
| Server | XAMPP (Apache 2.4.58, PHP 8.0.30) |
| Databaza | MySQL 5.7+ |
| Browser | Chrome 120, Firefox 121, Edge 120 |
| Sistemi Operativ | Windows 11 |
| Rezolucioni | 1920x1080 (desktop), 375px (mobile) |

---

## 3. Rastet e Testimit

### 3.1 Autentikimi (RF-01)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-01 | Regjistrim me te dhena valide | Emri, email valid, password 8+ char | Llogaria krijohet, email mireseardhjeje | Si pritej | PASS |
| T-02 | Regjistrim me email duplikat | Email ekzistues | Mesazh gabimi | Si pritej | PASS |
| T-03 | Regjistrim me password te shkurter | Password < 8 char | Mesazh validimi | Si pritej | PASS |
| T-04 | Login me kredenciale valide | Email + password korrekt | Hyrje e suksesshme, redirect dashboard | Si pritej | PASS |
| T-05 | Login me password te gabuar | Password i gabuar | Mesazh gabimi | Si pritej | PASS |
| T-06 | Logout | Kliko logout | Sesioni shkatrrohet, redirect index | Si pritej | PASS |
| T-07 | Akses admin si klient | URL admin.php direkt | Redirect, akses i refuzuar | Si pritej | PASS |
| T-08 | Ndryshim fjalekalimi | Password i vjeter + i ri | Fjalekalimi perditësohet | Si pritej | PASS |

### 3.2 Menaxhimi i Pakove (RF-02, RF-03)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-09 | Krijim pakoje me te dhena valide | 4-step wizard i plotesuar | Pakoja krijohet, tracking code gjenderohet | Si pritej | PASS |
| T-10 | Krijim pakoje me fusha bosh | Fusha te detyrueshme bosh | Mesazh validimi | Si pritej | PASS |
| T-11 | Gjurmim me kod valid | Tracking code ekzistues | Statusi dhe historiku shfaqen | Si pritej | PASS |
| T-12 | Gjurmim me kod invalid | Kod i gabuar | Mesazh gabimi | Si pritej | PASS |
| T-13 | Filtrim i listës pakove | Filter sipas statusit | Lista filtrohet saktesisht | Si pritej | PASS |
| T-14 | Kerkimi ne liste | Kerkese me tracking code | Rezultati i sakte shfaqet | Si pritej | PASS |
| T-15 | Update statusi nga admini | Ndryshim status + shenime | Statusi perditësohet, email dërgohet | Si pritej | PASS |

### 3.3 Pagesat (RF-03)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-16 | Pagese PayPal Sandbox | Klikimi PayPal Button | Order krijohet, pagesa procesohet | Si pritej | PASS |
| T-17 | Capture order | Konfirmim pageses | payment_status='paid', email dërgohet | Si pritej | PASS |
| T-18 | Anulim pagese | Kliko Cancel ne PayPal | Pagesa anulohet, statusi mbetet unpaid | Si pritej | PASS |

### 3.4 PDF dhe Etiketa (RF-03)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-19 | Gjenerim etikete PDF | Kliko "Shkarko Etiketen" | PDF shkarkohet me QR Code | Si pritej | PASS |
| T-20 | Akses etikete nga perdorues tjeter | URL direkt me ID te ndryshme | Akses i refuzuar | Si pritej | PASS |

### 3.5 Email Njoftime (RF-05)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-21 | Email mireseardhje | Regjistrim i ri | Email dérgohet ne adresën e re | Si pritej | PASS |
| T-22 | Email konfirmimi pakoje | Pakoje e re e krijuar | Email tek derguesi + marrësi | Si pritej | PASS |
| T-23 | Email update statusi | Admini ndryshon statusin | Email tek pronari i paketes | Si pritej | PASS |
| T-24 | Email pas pageses | Pagese e suksesshme | Email me etikete PDF attached | Si pritej | PASS |

### 3.6 Panel Administratori (RF-07)

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-25 | Shfaqja e statistikave | Hyrje ne panel | 4 statcards me te dhena korrekte | Si pritej | PASS |
| T-26 | Lista perdoruesve | Tab "Perdoruesit" | Lista e plote me filtrim | Si pritej | PASS |
| T-27 | Fshirje perdoruesi | Kliko "Fshi" | Perdoruesi fshihet, vetveten nuk mund ta fshije | Si pritej | PASS |
| T-28 | Lista mesazheve kontakt | Tab "Mesazhet" | Mesazhet shfaqen me detaje | Si pritej | PASS |

### 3.7 Siguria

| ID | Rasti i Testimit | Input | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|-------|-------------------|------------------|---------|
| T-29 | SQL Injection | ' OR 1=1 -- ne fushat input | Kerkesa refuzohet, PDO e mbron | Si pritej | PASS |
| T-30 | XSS Attack | Script tag ne fushat input | Kodi nuk ekzekutohet | Si pritej | PASS |
| T-31 | Akses direkt API pa sesion | URL direkt tek api/*.php | JSON error, redirect | Si pritej | PASS |
| T-32 | Ownership check | Akses pakoje se tjetrit | Akses i refuzuar | Si pritej | PASS |

### 3.8 UI/UX dhe Responsive

| ID | Rasti i Testimit | Pajisja | Rezultati i Pritur | Rezultati Aktual | Statusi |
|----|-----------------|---------|-------------------|------------------|---------|
| T-33 | Responsive desktop | 1920x1080 | Layout korrekt | Si pritej | PASS |
| T-34 | Responsive mobile | 375px (iPhone SE) | Layout adaptive | Si pritej | PASS |
| T-35 | Responsive tablet | 768px | Layout adaptive | Si pritej | PASS |
| T-36 | Navbar dropdown | Klik Account menu | Dropdown shfaqet saktesisht | Si pritej | PASS |

---

## 4. Permbledhja e Rezultateve

| Kategoria | Total Teste | PASS | FAIL | Shkalla Suksesit |
|-----------|-------------|------|------|-----------------|
| Autentikimi | 8 | 8 | 0 | 100% |
| Menaxhimi Pakove | 7 | 7 | 0 | 100% |
| Pagesat | 3 | 3 | 0 | 100% |
| PDF Etiketa | 2 | 2 | 0 | 100% |
| Email Njoftime | 4 | 4 | 0 | 100% |
| Panel Admin | 4 | 4 | 0 | 100% |
| Siguria | 4 | 4 | 0 | 100% |
| UI/UX Responsive | 4 | 4 | 0 | 100% |
| **TOTAL** | **36** | **36** | **0** | **100%** |

---

## 5. Defekte te Gjendura dhe te Rregulluara

| ID | Pershkrimi | Severiteti | Statusi |
|----|-----------|------------|---------|
| BUG-01 | Config.php ngarkohej ne GitHub me kredenciale | I larte | Rregulluar – shtuar ne .gitignore |
| BUG-02 | DB_NAME nuk perputhet mes config dhe databazës lokale | Mesatar | Rregulluar – perditesuar ne postawebIS |

---

## 6. Konkluzionet
Platforma PostaWeb kaloi te gjitha 36 rastet e testimit me sukses. Sistemi eshte funksional, i sigurt dhe responsive. Dy defekte te vogla te gjetura gjate konfigurimit fillestar u rregulluan menjehere.

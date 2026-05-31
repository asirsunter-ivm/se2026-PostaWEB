# User Manual
## PostaWeb - Platforma e Sherbimit Postar

**Ekipi:** Finite Loop  
**Versioni:** v1.0  
**Data:** 16 Prill 2025  

---

## 1. Hyrje

PostaWeb eshte nje platforme web per menaxhimin e sherbimeve postare. Ky manual pershkruan si te perdoret platforma nga klientet dhe administratoret.

---

## 2. Perdoruesi – Klienti

### 2.1 Regjistrim

1. Hap faqen kryesore te PostaWeb
2. Kliko **"Regjistrohu"** ne navbar
3. Ploteso formularin:
   - Emri i plote
   - Adresa email
   - Fjalekalimi (min 8 karaktere)
   - Konfirmo fjalekalimin
4. Kliko **"Regjistrohu"**
5. Merr email mireseardhjeje automatikisht

### 2.2 Hyrje ne Sistem

1. Kliko **"Hyr"** ne navbar
2. Vendos email dhe fjalekalimin
3. Kliko **"Hyr"**
4. Ridrejtohet automatikisht tek Dashboard

### 2.3 Gjurmimi i Pakoses

**Metoda 1 – Pa llogari (publike):**
1. Hap faqen kryesore
2. Vendos kodin tracking ne fushen e kerkimit
3. Kliko **"Gjurmo"**

**Metoda 2 – Me llogari:**
1. Hyr ne llogari
2. Shko tek Dashboard → Lista e Pakove
3. Kliko mbi pakon per te pare detajet

### 2.4 Dergimi i nje Pakoje

1. Hyr ne llogari
2. Kliko **"Dergo Pako te Re"** ne Dashboard
3. Ploteso 4 hapat e wizard-it:
   - **Hapi 1:** Te dhenat e derguesit
   - **Hapi 2:** Te dhenat e marrësit
   - **Hapi 3:** Detajet e pakoses (pesha, tipi)
   - **Hapi 4:** Konfirmimi dhe pagesa
4. Kliko **"Konfirmo"**
5. Merr email konfirmimi me kodin tracking

### 2.5 Pagesa

1. Pas konfirmimit te pakoses, kliko **"Paguaj"**
2. Klikoni butonin **PayPal**
3. Hyni ne llogarinë tuaj PayPal Sandbox
4. Konfirmoni pagesen
5. Merr email konfirmimi me etikete PDF attached

### 2.6 Shkarko Etiketen PDF

1. Hyr ne Dashboard
2. Gjej pakosen e paguar
3. Kliko **"Shkarko Etiketen"**
4. PDF shkarkohet automatikisht

### 2.7 Profili

1. Kliko emrin ne navbar → **"Profili"**
2. Ndrysho emrin ose numrin e telefonit
3. Per ndryshim fjalekalimi:
   - Vendos fjalekalimin e vjeter
   - Vendos fjalekalimin e ri (min 8 karaktere)
   - Konfirmo fjalekalimin e ri
4. Kliko **"Ruaj Ndryshimet"**

### 2.8 Kontakti dhe Vleresimi

1. Shko tek seksioni **"Kontakt"** ne faqen kryesore
2. Ploteso formularin e kontaktit
3. Per vleresim, zgjedh numrin e yjeve (1-5) dhe shkruaj komentin
4. Kliko **"Dergo"**

---

## 3. Perdoruesi – Administratori

### 3.1 Hyrje si Administrator

1. Hap faqen kryesore
2. Kliko **"Hyr"**
3. Vendos kredencialet e adminit
4. Ridrejtohet automatikisht tek Panel Administratori

### 3.2 Dashboard Administratori

Dashboard shfaq 4 statcards:
- **Perdoruesit Total** – numri i te gjithe klienteve
- **Paketat Total** – numri i te gjitha pakove
- **Te Ardhurat** – totali i pagesave te kryera
- **Paketat Aktive** – pako ne procesim ose transit

### 3.3 Menaxhimi i Pakove

1. Kliko tab **"Pako"** ne panel
2. Shiko listen e te gjitha pakove
3. Filtro sipas statusit ose kerko me tracking code
4. Kliko mbi nje pako per te ndryshuar statusin:
   - pending → processing → in_transit → out_for_delivery → delivered
5. Shto shenime opsionale
6. Kliko **"Perditeso"** – klienti merr email automatikisht

### 3.4 Menaxhimi i Perdoruesve

1. Kliko tab **"Perdoruesit"**
2. Shiko listen e te gjithe klienteve
3. Per fshirje: kliko **"Fshi"** (nuk mund te fshish veten)

### 3.5 Mesazhet e Kontaktit

1. Kliko tab **"Mesazhet"**
2. Shiko te gjitha mesazhet e dërguara nga klientet
3. Kontrollo detajet (emri, email, subjekti, mesazhi)

---

## 4. Kodet e Statusit te Pakoses

| Statusi | Pershkrimi |
|---------|------------|
| pending | Pakoja e re, pret procesim |
| processing | Duke u procesuar nga stafi |
| in_transit | Ne rruge drejt destinacionit |
| out_for_delivery | Dërgesa ne progres |
| delivered | Dorezuar me sukses |
| returned | Kthyer mbrapa |
| cancelled | Anuluar |

---

## 5. Pyetje te Shpeshta (FAQ)

**Si ta gjej kodin tracking?**  
Kodi tracking eshte derguar ne email-in e konfirmimit pas krijimit te pakoses. Eshte ne formatin `PW20250220A3F2B1`.

**Cfare ndodh nese harroj fjalekalimin?**  
Kliko **"Harrove fjalekalimin?"** ne faqen e hyrjes dhe vendos email-in. Do te marrësh nje link per rivendosje.

**A mund te gjurmoj pakon pa llogari?**  
Po, faqja kryesore ka nje forme gjurmimi publik ku vendos kodin tracking.

**Si te kontaktoj mbeshtetjen?**  
Perdor formen e kontaktit ne faqen kryesore ose dergo email tek postaweb.finiteloop@gmail.com.

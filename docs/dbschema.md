# Database Schema
## PostaWeb - Platforma e Sherbimit Postar

**Ekipi:** Finite Loop  
**Versioni:** v1.0  
**Data:** 20 Shkurt 2025  
**Databaza:** postawebIS  
**Normalizimi:** Forma e Trete Normale (3NF)  

---

## Tabelat

### 1. users
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| name | VARCHAR(100) | NOT NULL | Emri i plote |
| email | VARCHAR(150) | NOT NULL, UNIQUE | Email (perdoret per login) |
| password | VARCHAR(255) | NOT NULL | Fjalekalimi bcrypt |
| phone | TEXT | NULL | Telefoni i enkriptuar AES-256 |
| role | ENUM | 'client','admin' | Roli ne sistem |
| created_at | TIMESTAMP | DEFAULT NOW() | Data regjistrimit |

### 2. packages
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| tracking_code | VARCHAR(30) | NOT NULL, UNIQUE | Kodi gjurmimi (PW+date+hex) |
| user_id | INT | FK → users.id | Pronari |
| package_type_id | INT | FK → package_types.id | Tipi paketes |
| sender_name | VARCHAR(100) | NOT NULL | Emri derguesit |
| sender_address | TEXT | NOT NULL | Adresa derguesit (AES-256) |
| sender_phone | TEXT | NULL | Telefoni derguesit (AES-256) |
| recipient_name | VARCHAR(100) | NOT NULL | Emri marrësit |
| recipient_address | TEXT | NOT NULL | Adresa marrësit (AES-256) |
| recipient_phone | TEXT | NULL | Telefoni marrësit (AES-256) |
| recipient_email | VARCHAR(150) | NULL | Email marrësit |
| country_id | INT | FK → countries.id | Vendi destinacionit |
| city_id | INT | FK → cities.id | Qyteti destinacionit |
| weight | DECIMAL(8,2) | NOT NULL | Pesha ne kg |
| cost | DECIMAL(10,2) | NOT NULL | Kostoja e llogarritur |
| status | ENUM | pending/processing/in_transit/out_for_delivery/delivered/returned/cancelled | Statusi aktual |
| payment_status | ENUM | 'unpaid','paid' | Statusi pageses |
| notes | TEXT | NULL | Shenime shtese |
| created_at | TIMESTAMP | DEFAULT NOW() | Data krijimit |

### 3. tracking_history
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| package_id | INT | FK → packages.id | Paketa perkatese |
| status | VARCHAR(50) | NOT NULL | Statusi i ri |
| note | TEXT | NULL | Shenime ndryshimit |
| updated_by | INT | FK → users.id | Admini qe beri ndryshimin |
| created_at | TIMESTAMP | DEFAULT NOW() | Data ndryshimit |

### 4. package_types
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| name | VARCHAR(100) | NOT NULL | Emri tipit |
| base_price | DECIMAL(10,2) | NOT NULL | Cmimi baze |
| description | TEXT | NULL | Pershkrimi |

### 5. countries
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| name | VARCHAR(100) | NOT NULL | Emri vendit |
| code | VARCHAR(5) | NOT NULL, UNIQUE | Kodi vendit (AL, XK) |
| shipping_multiplier | DECIMAL(4,2) | DEFAULT 1.00 | Shumezuesi cmimit |

### 6. cities
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| name | VARCHAR(100) | NOT NULL | Emri qytetit |
| country_id | INT | FK → countries.id | Vendi perkatës |

### 7. notifications
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| user_id | INT | FK → users.id | Perdoruesi marrës |
| type | VARCHAR(50) | NOT NULL | Lloji njoftimit |
| message | TEXT | NOT NULL | Teksti njoftimit |
| is_read | TINYINT(1) | DEFAULT 0 | 0=palexuar, 1=lexuar |
| created_at | TIMESTAMP | DEFAULT NOW() | Data krijimit |

### 8. contact_messages
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| user_id | INT | FK → users.id, NULL | Perdoruesi (NULL nese i paidentifikuar) |
| name | VARCHAR(100) | NOT NULL | Emri derguesit |
| email | VARCHAR(150) | NOT NULL | Email derguesit |
| subject | VARCHAR(200) | NOT NULL | Subjekti |
| message | TEXT | NOT NULL | Permbajtja |
| created_at | TIMESTAMP | DEFAULT NOW() | Data dergimit |

### 9. reviews
| Kolona | Tipi | Atributet | Pershkrimi |
|--------|------|-----------|------------|
| id | INT | AUTO_INCREMENT, PK | Identifikuesi unik |
| user_id | INT | FK → users.id | Perdoruesi |
| rating | TINYINT | NOT NULL (1-5) | Vleresimi me yje |
| comment | TEXT | NULL | Komenti opsional |
| created_at | TIMESTAMP | DEFAULT NOW() | Data vleresimit |

---

## Marredheniet (Foreign Keys)

| Tabela | Kolona | Referon |
|--------|--------|---------|
| packages | user_id | users.id |
| packages | package_type_id | package_types.id |
| packages | country_id | countries.id |
| packages | city_id | cities.id |
| tracking_history | package_id | packages.id |
| tracking_history | updated_by | users.id |
| notifications | user_id | users.id |
| contact_messages | user_id | users.id |
| reviews | user_id | users.id |

---

## Llogaritja e Kostos
**Formula:** `Kosto = (Pesha_kg x base_price) x shipping_multiplier`

## Kodi Tracking
**Formati:** `PW + YYYYMMDD + 6_karaktere_hex`  
**Shembull:** `PW20250220A3F2B1`

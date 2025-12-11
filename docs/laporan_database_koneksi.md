# LAPORAN PEMBAHASAN PROYEK FLEXORA

## Rancangan Basis Data dan Koneksi Frontend-Backend

**Nama Proyek:** Flexora - Sistem Manajemen Studio Kebugaran  
**Database:** MySQL (via XAMPP)  
**Framework:** Laravel

---

## 1. RANCANGAN BASIS DATA

### 1.1 Gambaran Umum Sistem Basis Data

Sistem Flexora menggunakan **MySQL** sebagai Database Management System (DBMS) yang dijalankan melalui **XAMPP**. XAMPP menyediakan lingkungan pengembangan lokal yang lengkap dengan:

- **Apache** sebagai web server
- **MySQL** sebagai database server
- **PHP** sebagai bahasa pemrograman

MySQL dipilih karena:

- Mudah diinstall dan dikonfigurasi
- Kompatibel dengan Laravel framework
- Performa yang baik untuk aplikasi web
- Dokumentasi yang lengkap dan komunitas yang besar

Database dirancang menggunakan pendekatan **normalisasi** untuk:

- Mengurangi redundansi data
- Memastikan integritas referensial antar tabel
- Mempermudah maintenance dan update data

### 1.2 Struktur Tabel Database

Sistem Flexora memiliki **7 tabel utama** yang saling berelasi. Berikut adalah penjelasan detail dari setiap tabel:

#### 1.2.1 Tabel `users`

Tabel ini menyimpan informasi pengguna sistem, baik member maupun admin.

| Kolom               | Tipe Data           | Keterangan                    |
| ------------------- | ------------------- | ----------------------------- |
| `id`                | BIGINT (PK)         | Primary Key, auto-increment   |
| `name`              | VARCHAR(255)        | Nama lengkap pengguna         |
| `email`             | VARCHAR(255) UNIQUE | Email pengguna (untuk login)  |
| `email_verified_at` | TIMESTAMP NULL      | Waktu verifikasi email        |
| `password`          | VARCHAR(255)        | Password terenkripsi (hashed) |
| `role`              | VARCHAR(255)        | Peran pengguna (user/admin)   |
| `remember_token`    | VARCHAR(100) NULL   | Token untuk "remember me"     |
| `created_at`        | TIMESTAMP           | Waktu pembuatan record        |
| `updated_at`        | TIMESTAMP           | Waktu update terakhir         |

**Fungsi**: Mengelola data pengguna, autentikasi, dan otorisasi sistem.

---

#### 1.2.2 Tabel `studio_classes`

Tabel ini menyimpan informasi jenis kelas yang tersedia di studio.

| Kolom         | Tipe Data           | Keterangan                            |
| ------------- | ------------------- | ------------------------------------- |
| `id`          | BIGINT (PK)         | Primary Key, auto-increment           |
| `name`        | VARCHAR(255)        | Nama kelas (Yoga, Pilates, HIIT, dll) |
| `slug`        | VARCHAR(255) UNIQUE | URL-friendly identifier               |
| `description` | TEXT                | Deskripsi lengkap tentang kelas       |
| `image`       | VARCHAR(255) NULL   | Path file gambar kelas                |
| `created_at`  | TIMESTAMP           | Waktu pembuatan record                |
| `updated_at`  | TIMESTAMP           | Waktu update terakhir                 |

**Fungsi**: Master data untuk jenis kelas yang ditawarkan studio.

---

#### 1.2.3 Tabel `schedules`

Tabel ini menyimpan jadwal kelas yang tersedia.

| Kolom             | Tipe Data    | Keterangan                          |
| ----------------- | ------------ | ----------------------------------- |
| `id`              | BIGINT (PK)  | Primary Key, auto-increment         |
| `studio_class_id` | BIGINT (FK)  | Foreign Key ke tabel studio_classes |
| `instructor`      | VARCHAR(255) | Nama instruktur                     |
| `start_time`      | DATETIME     | Waktu mulai kelas                   |
| `end_time`        | DATETIME     | Waktu selesai kelas                 |
| `price`           | DECIMAL(8,2) | Harga per sesi (untuk non-member)   |
| `created_at`      | TIMESTAMP    | Waktu pembuatan record              |
| `updated_at`      | TIMESTAMP    | Waktu update terakhir               |

**Fungsi**: Mengelola jadwal kelas beserta informasi instruktur dan harga.

**Relasi**:

- `studio_class_id` → `studio_classes(id)` dengan `ON DELETE CASCADE`

---

#### 1.2.4 Tabel `memberships`

Tabel ini menyimpan paket membership yang tersedia.

| Kolom           | Tipe Data         | Keterangan                          |
| --------------- | ----------------- | ----------------------------------- |
| `id`            | BIGINT (PK)       | Primary Key, auto-increment         |
| `name`          | VARCHAR(255)      | Nama paket (Silver, Gold, Platinum) |
| `price`         | INTEGER UNSIGNED  | Harga membership                    |
| `duration_days` | INTEGER UNSIGNED  | Durasi membership dalam hari        |
| `description`   | VARCHAR(255) NULL | Deskripsi benefit membership        |
| `created_at`    | TIMESTAMP         | Waktu pembuatan record              |
| `updated_at`    | TIMESTAMP         | Waktu update terakhir               |

**Fungsi**: Master data untuk paket keanggotaan yang ditawarkan.

---

#### 1.2.5 Tabel `user_memberships`

Tabel ini mencatat membership aktif yang dimiliki pengguna.

| Kolom           | Tipe Data   | Keterangan                       |
| --------------- | ----------- | -------------------------------- |
| `id`            | BIGINT (PK) | Primary Key, auto-increment      |
| `user_id`       | BIGINT (FK) | Foreign Key ke tabel users       |
| `membership_id` | BIGINT (FK) | Foreign Key ke tabel memberships |
| `start_date`    | DATE        | Tanggal mulai membership         |
| `end_date`      | DATE        | Tanggal berakhir membership      |
| `created_at`    | TIMESTAMP   | Waktu pembuatan record           |
| `updated_at`    | TIMESTAMP   | Waktu update terakhir            |

**Fungsi**: Mencatat riwayat pembelian membership dan status membership aktif user.

**Relasi**:

- `user_id` → `users(id)` dengan `ON DELETE CASCADE`
- `membership_id` → `memberships(id)` dengan `ON DELETE CASCADE`

---

#### 1.2.6 Tabel `enrollments`

Tabel ini mencatat pendaftaran pengguna ke kelas tertentu.

| Kolom             | Tipe Data         | Keterangan                                 |
| ----------------- | ----------------- | ------------------------------------------ |
| `id`              | BIGINT (PK)       | Primary Key, auto-increment                |
| `user_id`         | BIGINT (FK)       | Foreign Key ke tabel users                 |
| `schedule_id`     | BIGINT (FK)       | Foreign Key ke tabel schedules             |
| `status`          | VARCHAR(255)      | Status booking (pending/paid/cancelled)    |
| `enrollment_type` | VARCHAR(255) NULL | Tipe enrollment (membership/pay-per-class) |
| `created_at`      | TIMESTAMP         | Waktu pembuatan record                     |
| `updated_at`      | TIMESTAMP         | Waktu update terakhir                      |

**Fungsi**: Mencatat pendaftaran/booking kelas oleh user, baik menggunakan membership atau bayar per kelas.

**Relasi**:

- `user_id` → `users(id)` dengan `ON DELETE CASCADE`
- `schedule_id` → `schedules(id)` dengan `ON DELETE CASCADE`

---

#### 1.2.7 Tabel `membership_studio_class` (Junction Table)

Tabel pivot untuk relasi many-to-many antara memberships dan studio_classes.

| Kolom             | Tipe Data   | Keterangan                          |
| ----------------- | ----------- | ----------------------------------- |
| `id`              | BIGINT (PK) | Primary Key, auto-increment         |
| `membership_id`   | BIGINT (FK) | Foreign Key ke tabel memberships    |
| `studio_class_id` | BIGINT (FK) | Foreign Key ke tabel studio_classes |
| `created_at`      | TIMESTAMP   | Waktu pembuatan record              |
| `updated_at`      | TIMESTAMP   | Waktu update terakhir               |

**Constraint**: UNIQUE(`membership_id`, `studio_class_id`)

**Fungsi**: Menentukan kelas mana saja yang dapat diakses oleh pemegang membership tertentu.

**Relasi**:

- `membership_id` → `memberships(id)` dengan `ON DELETE CASCADE`
- `studio_class_id` → `studio_classes(id)` dengan `ON DELETE CASCADE`

---

### 1.3 Entity Relationship Diagram (ERD)

Berikut adalah diagram ERD yang menunjukkan relasi antar tabel dalam database:

![Entity Relationship Diagram Flexora](C:/Users/USER/.gemini/antigravity/brain/3db41993-d78b-4eca-8a00-8479ba74f556/erd_flexora_database_1765379741313.png)

**Keterangan:**

- **PK** = Primary Key
- **FK** = Foreign Key
- Garis penghubung menunjukkan relasi antar tabel
- Simbol crow's foot menunjukkan kardinalitas relasi (one-to-many)

---

### 1.4 Relasi Antar Tabel

#### One-to-Many Relationships:

1. **users → user_memberships** (1:N)

   - Satu user dapat memiliki banyak membership (riwayat pembelian)
   - Implementasi: kolom `user_id` di tabel `user_memberships`

2. **users → enrollments** (1:N)

   - Satu user dapat mendaftar ke banyak kelas
   - Implementasi: kolom `user_id` di tabel `enrollments`

3. **memberships → user_memberships** (1:N)

   - Satu paket membership dapat dibeli oleh banyak user
   - Implementasi: kolom `membership_id` di tabel `user_memberships`

4. **studio_classes → schedules** (1:N)

   - Satu jenis kelas dapat memiliki banyak jadwal
   - Implementasi: kolom `studio_class_id` di tabel `schedules`

5. **schedules → enrollments** (1:N)
   - Satu jadwal dapat diikuti oleh banyak user
   - Implementasi: kolom `schedule_id` di tabel `enrollments`

#### Many-to-Many Relationships:

1. **memberships ↔ studio_classes** (N:M)
   - Satu membership dapat mencakup akses ke banyak kelas
   - Satu kelas dapat diakses oleh banyak jenis membership
   - Implementasi: Menggunakan junction table `membership_studio_class`

---

### 1.5 Constraint dan Validasi

1. **Foreign Key Constraints dengan CASCADE DELETE:**

   - Ketika user dihapus, semua user_memberships dan enrollments miliknya akan otomatis terhapus
   - Ketika schedule dihapus, semua enrollment terkait akan otomatis terhapus
   - Ini memastikan tidak ada orphan records (data yang tidak terhubung) di database

2. **Unique Constraints:**

   - `users.email`: Memastikan setiap email unik dalam sistem (tidak boleh duplikat)
   - `studio_classes.slug`: Memastikan URL-friendly identifier unik untuk setiap kelas
   - `membership_studio_class(membership_id, studio_class_id)`: Mencegah duplikasi relasi membership-kelas

3. **Default Values:**

   - `enrollments.status` default: 'pending' (status awal saat user booking kelas)

4. **Nullable Fields:**
   - `users.email_verified_at`, `users.remember_token`
   - `studio_classes.image`
   - `memberships.description`
   - `enrollments.enrollment_type`

---

## 2. KONEKSI FRONTEND-BACKEND

### 2.1 Arsitektur Aplikasi

Flexora menggunakan framework **Laravel** yang mengimplementasikan arsitektur **MVC (Model-View-Controller)**. Arsitektur ini memisahkan aplikasi menjadi tiga komponen utama:

1. **Backend (Server-Side)**: Laravel dengan PHP
2. **Frontend (Client-Side)**: Blade Templates + JavaScript
3. **Database**: MySQL (via XAMPP)

Berikut adalah diagram arsitektur koneksi frontend-backend:

![Arsitektur Frontend-Backend Flexora](C:/Users/USER/.gemini/antigravity/brain/3db41993-d78b-4eca-8a00-8479ba74f556/arsitektur_frontend_backend_1765379764084.png)

---

### 2.2 Komponen Arsitektur MVC

#### **2.2.1 Model (Backend - Database Layer)**

**Definisi:** Model adalah representasi tabel database dalam bentuk class PHP. Model bertugas untuk berkomunikasi dengan database.

**Fungsi:**

- Mengambil data dari database (SELECT)
- Menyimpan data ke database (INSERT)
- Mengupdate data (UPDATE)
- Menghapus data (DELETE)
- Mendefinisikan relasi antar tabel

**Contoh:** Model `User`, `StudioClass`, `Enrollment`, `Membership`

**Lokasi File:** `app/Models/`

---

#### **2.2.2 View (Frontend - Presentation Layer)**

**Definisi:** View adalah tampilan yang dilihat oleh user di browser. View menggunakan **Blade** sebagai templating engine.

**Fungsi:**

- Menampilkan data dalam format HTML
- Menerima input dari user (form)
- Menampilkan informasi secara dinamis

**Komponen View:**

- **HTML**: Struktur halaman
- **CSS**: Styling dan tampilan
- **JavaScript**: Interaktivitas dan AJAX
- **Blade Syntax**: Templating Laravel ({{ }}, @if, @foreach, dll)

**Contoh:** `dashboard.blade.php`, `home.blade.php`, `booking.blade.php`

**Lokasi File:** `resources/views/`

---

#### **2.2.3 Controller (Backend - Logic Layer)**

**Definisi:** Controller adalah penghubung antara Model dan View. Controller menangani logika aplikasi.

**Fungsi:**

- Menerima request dari user
- Memvalidasi input
- Memanggil Model untuk operasi database
- Mengirim data ke View
- Mengembalikan response (HTML atau JSON)

**Contoh:** `DashboardController`, `EnrollmentController`, `AuthController`

**Lokasi File:** `app/Http/Controllers/`

---

### 2.3 Flow Komunikasi Frontend-Backend

#### **2.3.1 Web Request (Halaman Lengkap)**

Alur komunikasi ketika user mengakses halaman web:

```
1. User membuka browser → akses URL (contoh: https://localhost/dashboard)
2. Browser mengirim HTTP Request ke server Laravel
3. Laravel Router (web.php) menerima request
4. Router mengarahkan ke Controller yang sesuai
5. Controller memproses request:
   - Memanggil Model untuk ambil data dari database
   - Database (MySQL) mengembalikan data
   - Model mengembalikan data ke Controller
6. Controller mengirim data ke View (Blade template)
7. Blade template me-render HTML dengan data yang diterima
8. Server mengirim HTML lengkap ke Browser
9. Browser menampilkan halaman ke user
```

**Contoh Kasus:**  
User mengakses halaman Dashboard → Controller mengambil data kelas yang sudah di-booking → View menampilkan list kelas user

---

#### **2.3.2 API Request (Data JSON via AJAX)**

Alur komunikasi ketika JavaScript meminta data tanpa reload halaman:

```
1. User melakukan aksi di halaman (contoh: klik tombol "Book Class")
2. JavaScript menangkap event dan mengirim AJAX request
3. Request dikirim ke Laravel API endpoint (contoh: /api/enrollments)
4. Laravel Router (api.php) menerima request
5. Middleware memverifikasi autentikasi (JWT token)
6. Router mengarahkan ke Controller yang sesuai
7. Controller memproses request:
   - Validasi input
   - Memanggil Model untuk simpan/ambil data
   - Database (MySQL) memproses query
8. Controller mengembalikan response dalam format JSON
9. JavaScript menerima response JSON
10. JavaScript mengupdate tampilan halaman (tanpa reload)
```

**Contoh Kasus:**  
User klik "Enroll to Class" → JavaScript kirim request → Server simpan data booking → JavaScript tampilkan pesan sukses

---

### 2.4 Jenis Routing

#### **2.4.1 Web Routes**

**File:** `routes/web.php`

**Fungsi:** Menangani request untuk halaman HTML lengkap

**Contoh:**

- `/` → Halaman Home
- `/dashboard` → Halaman Dashboard user
- `/login` → Halaman Login
- `/admin/dashboard` → Halaman Dashboard Admin

**Karakteristik:**

- Response berupa HTML lengkap
- Menggunakan session untuk autentikasi
- Cocok untuk navigasi halaman tradisional

---

#### **2.4.2 API Routes**

**File:** `routes/api.php`

**Fungsi:** Menangani request untuk data JSON (AJAX)

**Contoh:**

- `/api/auth/login` → Login dan dapat JWT token
- `/api/enrollments` → Ambil atau simpan data enrollment
- `/api/classes/available-by-date` → Ambil kelas berdasarkan tanggal

**Karakteristik:**

- Response berupa JSON
- Menggunakan JWT token untuk autentikasi
- Cocok untuk aplikasi SPA (Single Page Application) atau mobile app

---

### 2.5 Autentikasi

#### **2.5.1 Autentikasi Web (Session-based)**

Digunakan untuk halaman web biasa dan admin panel.

**Cara Kerja:**

1. User submit form login dengan email dan password
2. Server memvalidasi credentials dengan database
3. Jika valid, server membuat session dan menyimpan cookie di browser
4. Request berikutnya akan membawa session cookie
5. Server memverifikasi session untuk autentikasi

**Keuntungan:**

- Cocok untuk website tradisional
- Session disimpan di server, lebih aman

---

#### **2.5.2 Autentikasi API (JWT - JSON Web Token)**

Digunakan untuk API endpoint.

**Cara Kerja:**

1. User kirim credentials via AJAX
2. Server validasi dan membuat JWT token
3. Token dikirim ke client dalam response JSON
4. Client menyimpan token (localStorage/sessionStorage)
5. Setiap request API menyertakan token di header:
   ```
   Authorization: Bearer <token>
   ```
6. Server memverifikasi token untuk autentikasi

**Keuntungan:**

- Stateless (tidak perlu session di server)
- Cocok untuk API dan mobile app
- Token dapat digunakan di multiple devices

---

### 2.6 Request & Response Flow Sederhana

#### **Skenario 1: User Mengakses Dashboard**

```
Browser → Request ke /dashboard
  ↓
Router (web.php) → Cari route yang cocok
  ↓
DashboardController → Ambil data user dan enrollments
  ↓
Model (User, Enrollment) → Query database MySQL
  ↓
Database → Return data
  ↓
Controller → Kirim data ke View
  ↓
View (dashboard.blade.php) → Render HTML
  ↓
Browser ← Terima HTML lengkap → Tampilkan halaman
```

---

#### **Skenario 2: User Booking Kelas via AJAX**

```
User klik tombol "Book Class"
  ↓
JavaScript → Kirim POST request ke /api/enrollments
  ↓
Router (api.php) → Cari route yang cocok
  ↓
Middleware → Cek JWT token (autentikasi)
  ↓
EnrollmentController → Validasi input, simpan data
  ↓
Model (Enrollment) → INSERT ke database MySQL
  ↓
Database → Return hasil
  ↓
Controller → Return JSON {success: true, enrollment: {...}}
  ↓
JavaScript ← Terima JSON response
  ↓
JavaScript → Update tampilan tanpa reload halaman
```

---

### 2.7 Teknologi yang Digunakan

| Komponen               | Teknologi            | Fungsi                       |
| ---------------------- | -------------------- | ---------------------------- |
| **Web Server**         | Apache (XAMPP)       | Menjalankan aplikasi PHP     |
| **Database**           | MySQL (XAMPP)        | Menyimpan data aplikasi      |
| **Backend Framework**  | Laravel              | Framework PHP untuk backend  |
| **Frontend Template**  | Blade                | Template engine Laravel      |
| **Styling**            | CSS                  | Styling tampilan             |
| **Interaktivitas**     | JavaScript           | Interaksi user dan AJAX      |
| **Asset Bundler**      | Vite                 | Kompilasi CSS dan JavaScript |
| **API Authentication** | JWT (tymon/jwt-auth) | Autentikasi API              |
| **ORM**                | Eloquent             | Object-Relational Mapping    |

---

## 3. KESIMPULAN

### 3.1 Rancangan Basis Data

1. Sistem menggunakan **MySQL via XAMPP** dengan **7 tabel** yang saling berelasi
2. Implementasi **relasi one-to-many dan many-to-many** untuk mendukung fitur kompleks
3. **Normalisasi database** mengurangi redundansi dan meningkatkan integritas data
4. **Foreign key constraints dengan CASCADE** memastikan konsistensi data
5. **Eloquent ORM** mempermudah operasi database dengan syntax yang mudah dipahami

### 3.2 Koneksi Frontend-Backend

1. Menggunakan **arsitektur MVC (Model-View-Controller)** dengan framework Laravel
2. **Dual routing system**:
   - Web routes untuk halaman HTML lengkap
   - API routes untuk data JSON (AJAX)
3. **Blade templating engine** untuk server-side rendering
4. **AJAX** untuk komunikasi asynchronous (tanpa reload halaman)
5. **Dual authentication system**:
   - Session-based untuk web
   - JWT untuk API
6. **Pemisahan logika** yang jelas antara presentasi (View), logika (Controller), dan data (Model)

### 3.3 Keunggulan Arsitektur

1. **Separation of Concerns**: Logic terpisah dengan jelas (MVC)
2. **Scalability**: API dapat digunakan untuk mobile app atau SPA di masa depan
3. **Security**: Password hashing, CSRF protection, JWT authentication
4. **Developer Friendly**: Laravel menyediakan tools dan syntax yang mudah dipahami
5. **Maintainability**: Kode terstruktur, mudah di-maintain dan dikembangkan

---

## DAFTAR REFERENSI

1. Laravel Documentation: Database Migrations - https://laravel.com/docs/migrations
2. Laravel Documentation: Eloquent ORM - https://laravel.com/docs/eloquent
3. Laravel Documentation: Routing - https://laravel.com/docs/routing
4. Laravel Documentation: Blade Templates - https://laravel.com/docs/blade
5. JWT Authentication in Laravel - https://jwt-auth.readthedocs.io/
6. MySQL Official Documentation - https://dev.mysql.com/doc/
7. XAMPP Documentation - https://www.apachefriends.org/

---

**Catatan:** Gambar ERD dan Arsitektur Frontend-Backend tersedia dalam format PNG dan dapat diunduh dari folder artifact untuk disertakan dalam laporan final.

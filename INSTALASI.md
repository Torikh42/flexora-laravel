# 📦 Panduan Instalasi Flexora - Studio Fitness Booking System

> **Panduan ini dibuat untuk membantu menjalankan proyek Flexora di komputer/laptop lain**

---

## 📋 Daftar Isi

1. [Requirements (Kebutuhan Software)](#requirements)
2. [Langkah Instalasi](#langkah-instalasi)
3. [Konfigurasi Environment](#konfigurasi-environment)
4. [Menjalankan Aplikasi](#menjalankan-aplikasi)
5. [Akses & Testing](#akses--testing)
6. [Troubleshooting](#troubleshooting)

---

## 🔧 Requirements

Sebelum menjalankan proyek ini, pastikan software berikut sudah terinstall di komputer Anda:

### 1. **XAMPP** (Recommended) atau alternatif lain

-   **Download**: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
-   **Versi**: 8.2.x atau lebih baru
-   **Yang dibutuhkan**:
    -   PHP 8.2 atau lebih tinggi
    -   MySQL 8.0 atau lebih tinggi
-   **Cara Install**:
    1.  Download XAMPP installer
    2.  Jalankan installer dan ikuti petunjuk
    3.  Install di lokasi default (C:\xampp)
    4.  Start Apache dan MySQL dari XAMPP Control Panel

### 2. **Composer** (PHP Dependency Manager)

-   **Download**: [https://getcomposer.org/download/](https://getcomposer.org/download/)
-   **Cara Install**:
    1.  Download Composer-Setup.exe
    2.  Jalankan installer
    3.  Pilih PHP dari XAMPP (contoh: C:\xampp\php\php.exe)
    4.  Selesaikan instalasi
-   **Verifikasi**: Buka Command Prompt dan ketik `composer --version`

### 3. **Node.js** (JavaScript Runtime)

-   **Download**: [https://nodejs.org/](https://nodejs.org/)
-   **Versi**: 18.x atau lebih baru (gunakan LTS version)
-   **Cara Install**:
    1.  Download Node.js installer (Windows Installer .msi)
    2.  Jalankan installer dengan default settings
    3.  Restart komputer setelah instalasi
-   **Verifikasi**: Buka Command Prompt dan ketik:
    ```bash
    node --version
    npm --version
    ```

### 4. **Git** (Optional, untuk clone repository)

-   **Download**: [https://git-scm.com/download/win](https://git-scm.com/download/win)
-   Atau gunakan ZIP file yang sudah diekstrak

---

## 🚀 Langkah Instalasi

### Step 1: Extract Project

Jika proyek diberikan dalam bentuk ZIP:

```bash
# Extract file ZIP ke lokasi yang diinginkan
# Contoh: D:\flexora-laravel
```

### Step 2: Buka XAMPP Control Panel

1. Jalankan XAMPP Control Panel
2. **Start Apache** - untuk web server
3. **Start MySQL** - untuk database
4. Pastikan keduanya berwarna hijau (running)

### Step 3: Buat Database

1. Buka browser dan akses: `http://localhost/phpmyadmin`
2. Klik tab **"Databases"**
3. Buat database baru:
    - **Database name**: `flexora_laravel`
    - **Collation**: `utf8mb4_unicode_ci`
4. Klik **"Create"**

### Step 4: Install Dependencies

Buka **Command Prompt** atau **Terminal** dan navigasi ke folder project:

```bash
# Pindah ke folder project
cd D:\flexora-laravel

# Install PHP dependencies dengan Composer
composer install

# Install JavaScript dependencies dengan NPM
npm install
```

> **Catatan**: Proses ini membutuhkan koneksi internet dan bisa memakan waktu 5-10 menit tergantung kecepatan internet.

### Step 5: Setup Environment File

```bash
# Copy file .env.example menjadi .env
copy .env.example .env
```

Atau copy manual:

1. Cari file `.env.example` di folder project
2. Copy file tersebut
3. Paste di folder yang sama
4. Rename hasil copy menjadi `.env`

---

## ⚙️ Konfigurasi Environment

### Step 6: Edit File .env

Buka file `.env` dengan text editor (Notepad++, VSCode, atau Notepad) dan ubah konfigurasi berikut:

```env
APP_NAME=Flexora
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flexora_laravel
DB_USERNAME=root
DB_PASSWORD=

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue Configuration
QUEUE_CONNECTION=database

# Cache Configuration
CACHE_STORE=database
```

> **Penting**:
>
> -   `DB_PASSWORD` dikosongkan karena XAMPP default tanpa password
> -   Jika MySQL Anda memiliki password, isi sesuai password Anda

### Step 7: Generate Application Key & JWT Secret

Kembali ke Command Prompt di folder project, jalankan:

```bash
# Generate Laravel application key
php artisan key:generate

# Generate JWT secret untuk authentication
php artisan jwt:secret
```

Perintah ini akan otomatis mengisi `APP_KEY` di file `.env`

### Step 8: Migrasi Database & Seed Data

```bash
# Jalankan migrasi untuk membuat tabel-tabel database
php artisan migrate

# Isi database dengan data dummy (termasuk admin user)
php artisan db:seed
```

> **Data Admin Default**:
>
> -   Email: `admin@flexora.com`
> -   Password: `password`

### Step 9: Create Storage Link

```bash
# Membuat symbolic link untuk storage (upload gambar)
php artisan storage:link
```

### Step 10: Build Frontend Assets

```bash
# Build CSS dan JavaScript dengan Vite
npm run build
```

Untuk development mode (auto-reload):

```bash
npm run dev
```

---

## 🎯 Menjalankan Aplikasi

### Cara 1: Development Server (Recommended)

Di Command Prompt, jalankan:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Output akan muncul:

```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

**Jangan tutup Command Prompt** selama menggunakan aplikasi.

### Cara 2: Menggunakan XAMPP htdocs (Alternative)

1. Copy seluruh folder project ke `C:\xampp\htdocs\flexora`
2. Akses via browser: `http://localhost/flexora/public`

> **Catatan**: Cara 1 lebih disarankan untuk development.

---

## 🌐 Akses & Testing

### Akses Aplikasi

Buka browser (Chrome, Firefox, Edge) dan kunjungi:

```
http://localhost:8000
```

### Halaman yang Tersedia

1. **Home Page**: `http://localhost:8000/`
2. **Login**: `http://localhost:8000/login`
3. **Register**: `http://localhost:8000/signup`
4. **Classes**: `http://localhost:8000/studio_classes`
5. **Admin Panel**: `http://localhost:8000/admin/dashboard`

### Testing Admin Panel

1. Klik tombol **"Login"** di navbar
2. Masukkan credentials:
    ```
    Email: admin@flexora.com
    Password: password
    ```
3. Setelah login, akses: `http://localhost:8000/admin/dashboard`
4. Anda bisa mengelola:
    - Dashboard (statistik)
    - Classes (CRUD kelas fitness)
    - Memberships (CRUD paket membership)
    - Schedules (CRUD jadwal kelas)

### Testing Member Features

1. **Register** akun member baru di `/signup`
2. **Login** dengan akun tersebut
3. **Browse Classes** di `/studio_classes`
4. **Book Class** dengan klik tombol "Book Now"
5. **View Dashboard** untuk melihat bookings dan membership

---

## 🔍 Troubleshooting

### Problem 1: "composer: command not found"

**Solusi**:

1. Pastikan Composer sudah terinstall
2. Restart Command Prompt
3. Jika masih error, install ulang Composer

### Problem 2: "Node: command not found"

**Solusi**:

1. Pastikan Node.js sudah terinstall
2. Restart komputer
3. Cek environment variables (PATH harus include Node.js)

### Problem 3: Database Connection Error

**Solusi**:

```bash
# 1. Pastikan MySQL di XAMPP sudah running
# 2. Cek kredensial di .env
# 3. Clear cache Laravel
php artisan config:clear
php artisan cache:clear

# 4. Test koneksi database
php artisan migrate:status
```

### Problem 4: JWT Secret Error

**Solusi**:

```bash
# Regenerate JWT secret
php artisan jwt:secret --force
```

### Problem 5: Storage/Gambar Tidak Muncul

**Solusi**:

```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Set permissions (untuk Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### Problem 6: Assets CSS/JS Tidak Load

**Solusi**:

```bash
# Rebuild assets
npm run build

# Clear Laravel cache
php artisan optimize:clear
```

### Problem 7: Port 8000 Already in Use

**Solusi**:

```bash
# Gunakan port lain
php artisan serve --port=8080

# Lalu akses: http://localhost:8080
```

### Problem 8: Vendor Folder Tidak Ada

**Solusi**:

```bash
# Install ulang dependencies
composer install

# Jika ada error, hapus composer.lock dulu
del composer.lock
composer install
```

---

## 📊 Verifikasi Instalasi

Pastikan semua langkah berikut berhasil:

-   [ ] XAMPP Apache & MySQL running (hijau)
-   [ ] Database `flexora_laravel` terbuat di phpMyAdmin
-   [ ] `composer install` selesai tanpa error
-   [ ] `npm install` selesai tanpa error
-   [ ] File `.env` sudah dikonfigurasi dengan benar
-   [ ] `php artisan migrate` selesai (tabel terbuat)
-   [ ] `php artisan db:seed` selesai (data dummy terisi)
-   [ ] `php artisan serve` running tanpa error
-   [ ] Browser bisa mengakses `http://localhost:8000`
-   [ ] Login admin berhasil dengan `admin@flexora.com`
-   [ ] Dashboard admin bisa diakses

---

## 📝 Catatan Penting untuk Pengumpulan

### File yang Harus Disertakan dalam ZIP:

```
flexora-laravel/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── .env.example          ✅ PENTING: Jangan include .env
├── composer.json         ✅
├── package.json          ✅
├── README.md            ✅
├── INSTALASI.md         ✅ (File ini)
└── artisan              ✅
```

### File yang TIDAK Perlu Di-ZIP (karena akan di-generate):

-   `/vendor/` (akan dibuat saat `composer install`)
-   `/node_modules/` (akan dibuat saat `npm install`)
-   `.env` (akan dibuat dari `.env.example`)
-   `/storage/app/public/classes/` (upload folder)

### Estimasi Waktu Instalasi:

-   Download dependencies: 5-10 menit (tergantung internet)
-   Setup & konfigurasi: 5 menit
-   **Total**: ~15-20 menit untuk setup lengkap

---

## 📞 Informasi Tambahan

### Struktur Database

Aplikasi ini menggunakan tabel-tabel berikut:

-   `users` - Data pengguna (admin & member)
-   `studio_classes` - Daftar kelas fitness
-   `schedules` - Jadwal kelas
-   `enrollments` - Booking kelas oleh member
-   `memberships` - Paket membership
-   `user_memberships` - Membership yang dimiliki user

### Tech Stack

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Blade + Tailwind CSS + Vanilla JS
-   **Database**: MySQL 8.0
-   **Build Tool**: Vite
-   **Authentication**: JWT (tymon/jwt-auth)

---

## ✅ Checklist untuk Dosen

Sebelum menjalankan aplikasi, pastikan:

1. ✅ XAMPP terinstall dan MySQL + Apache running
2. ✅ Composer terinstall (cek: `composer --version`)
3. ✅ Node.js terinstall (cek: `node --version`)
4. ✅ Database `flexora_laravel` sudah dibuat
5. ✅ Semua command di "Langkah Instalasi" sudah dijalankan
6. ✅ File `.env` sudah dikonfigurasi
7. ✅ `php artisan serve` berjalan tanpa error
8. ✅ Bisa akses `http://localhost:8000` di browser

---

**Jika ada pertanyaan atau kendala, silakan hubungi developer.**

**Version**: 1.0  
**Created**: December 2025  
**Tested on**: Windows 10/11, XAMPP 8.2.12, PHP 8.2, Node.js 20.x

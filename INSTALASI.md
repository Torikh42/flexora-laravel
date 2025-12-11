# 📦 Panduan Instalasi Flexora

> **Panduan untuk menjalankan proyek Flexora di komputer/laptop Anda**

---

## 📋 Yang Dibutuhkan

Pastikan software berikut sudah terinstall:

### 1. XAMPP (PHP & MySQL)

-   **Download**: [apachefriends.org/download.html](https://www.apachefriends.org/download.html)
-   **Versi**: 8.2 atau lebih baru
-   Install dengan setting default

### 2. Composer (PHP Package Manager)

-   **Download**: [getcomposer.org/download](https://getcomposer.org/download/)
-   Saat install, pilih PHP dari XAMPP: `C:\xampp\php\php.exe`
-   Verifikasi: `composer --version`

### 3. Node.js (JavaScript Runtime)

-   **Download**: [nodejs.org](https://nodejs.org/) - pilih versi LTS
-   **Versi**: 18 atau lebih baru
-   Install dengan setting default, lalu **restart komputer**
-   Verifikasi: `node --version` dan `npm --version`

---

## 🚀 Langkah Instalasi

### 1. Extract Project

```bash
# Extract ZIP ke folder yang diinginkan
# Contoh: D:\flexora-laravel
```

### 2. Start XAMPP

1. Buka **XAMPP Control Panel**
2. Klik **Start** di Apache
3. Klik **Start** di MySQL
4. Pastikan keduanya berwarna hijau

### 3. Buat Database

1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik **"Databases"**
3. Database name: **`flexora`**
4. Collation: **`utf8mb4_unicode_ci`**
5. Klik **"Create"**

### 4. Install Dependencies & Setup

Buka **Command Prompt** di folder project:

```bash
# Masuk ke folder project
cd D:\flexora-laravel

# Install dependencies
composer install
npm install

# Setup database & build assets
php artisan migrate --seed
php artisan storage:link
npm run build
```

> Proses install butuh koneksi internet, waktu: ~5-10 menit

---

## 🎯 Menjalankan Aplikasi

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Buka browser: **`http://localhost:8000`**

> **Jangan tutup Command Prompt** selama menggunakan aplikasi

---

## 🔑 Login Admin

-   **URL**: `http://localhost:8000/admin/dashboard`
-   **Email**: `admin@flexora.com`
-   **Password**: `password`

---

## ⚠️ Troubleshooting

### Database Connection Error

```bash
php artisan config:clear
php artisan migrate:status
```

### Assets Tidak Load

```bash
npm run build
php artisan optimize:clear
```

### Port 8000 Sudah Digunakan

```bash
php artisan serve --port=8080
# Akses: http://localhost:8080
```

---

## ✅ Checklist Instalasi

-   [ ] XAMPP (Apache & MySQL) running
-   [ ] Database `flexora` sudah dibuat
-   [ ] `composer install` berhasil
-   [ ] `npm install` berhasil
-   [ ] `php artisan migrate --seed` berhasil
-   [ ] `php artisan serve` running
-   [ ] Browser bisa akses `http://localhost:8000`
-   [ ] Login admin berhasil

---

**Estimasi Waktu Total: 10-15 menit**

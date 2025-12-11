# ⚡ Quick Start - Flexora Deployment Checklist

> **Panduan cepat untuk menjalankan Flexora di komputer baru**

---

## 📋 Prerequisites Checklist

Pastikan software berikut sudah terinstall:

-   [ ] **XAMPP** (PHP 8.2+ & MySQL 8.0+) - [Download](https://www.apachefriends.org/download.html)
-   [ ] **Composer** - [Download](https://getcomposer.org/download/)
-   [ ] **Node.js** (v18+) - [Download](https://nodejs.org/)

---

## 🚀 Langkah Cepat (15 menit)

### 1️⃣ Persiapan (2 menit)

```bash
# Extract ZIP ke folder yang diinginkan
# Contoh: D:\flexora-laravel

# Buka XAMPP Control Panel
# ✅ Start Apache
# ✅ Start MySQL
```

### 2️⃣ Buat Database (1 menit)

```
1. Buka: http://localhost/phpmyadmin
2. Klik "Databases"
3. Database name: flexora
4. Collation: utf8mb4_unicode_ci
5. Klik "Create"
```

### 3️⃣ Install Dependencies (5-8 menit)

```bash
# Buka Command Prompt di folder project
cd D:\flexora-laravel

# Install Composer dependencies
composer install

# Install NPM dependencies
npm install
```

### 4️⃣ Setup Environment (1 menit)

```bash
# Setup database (isi dengan data dummy)
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build frontend assets
npm run build
```

### 5️⃣ Jalankan Aplikasi (1 menit)

```bash
# Start development server
php artisan serve --host=127.0.0.1 --port=8000

# Buka browser: http://localhost:8000
```

---

## 🔑 Akses Admin

```
URL: http://localhost:8000/admin/dashboard
Email: admin@flexora.com
Password: password
```

---

## ✅ Verifikasi

Akses halaman berikut untuk memastikan semuanya berjalan:

-   [ ] Home: `http://localhost:8000/`
-   [ ] Classes: `http://localhost:8000/studio_classes`
-   [ ] Login: `http://localhost:8000/login`
-   [ ] Admin Dashboard: `http://localhost:8000/admin/dashboard`

---

## ⚠️ Jika Ada Masalah

### Error: Database Connection

```bash
# Pastikan MySQL running di XAMPP
# Check file .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

php artisan config:clear
php artisan migrate:status
```

### Error: Assets Tidak Load

```bash
npm run build
php artisan optimize:clear
```

### Error: Storage/Images

```bash
php artisan storage:link
```

---

## 📝 Catatan untuk ZIP

### File yang HARUS ada dalam ZIP:

-   ✅ Semua folder (app, config, database, public, resources, routes, dll)
-   ✅ File `.env.example` (BUKAN `.env`)
-   ✅ File `composer.json`
-   ✅ File `package.json`
-   ✅ File `README.md`
-   ✅ File `INSTALASI.md`
-   ✅ File `DEPLOY_CHECKLIST.md`

### File yang TIDAK perlu di-ZIP (auto-generated):

-   ❌ `/vendor/`
-   ❌ `/node_modules/`
-   ❌ `.env`
-   ❌ `/bootstrap/cache/*.php`
-   ❌ `/storage/app/public/classes/*`

---

## 📊 Estimasi Waktu

| Tahap                   | Waktu            |
| ----------------------- | ---------------- |
| Extract & Persiapan     | 2 menit          |
| Buat Database           | 1 menit          |
| Install Dependencies    | 5-10 menit       |
| Setup Database & Assets | 1 menit          |
| Jalankan Aplikasi       | 1 menit          |
| **TOTAL**               | **~10-15 menit** |

---

**Untuk panduan lengkap, lihat file `INSTALASI.md`**

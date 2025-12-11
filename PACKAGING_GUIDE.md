# 📦 Panduan Packaging Project untuk Pengumpulan

> **File ini menjelaskan cara membuat ZIP project untuk pengumpulan**

---

## ✅ File/Folder yang HARUS Disertakan

```
flexora-laravel/
├── app/                    ✅ Semua file aplikasi
├── bootstrap/              ✅ (kecuali cache/*.php)
├── config/                 ✅ Konfigurasi Laravel
├── database/               ✅ Migrations & Seeders
│   ├── migrations/
│   ├── seeders/
│   └── .gitignore
├── public/                 ✅ Entry point & assets
│   ├── images/
│   ├── .htaccess
│   └── index.php
├── resources/              ✅ Views, CSS, JS
│   ├── css/
│   ├── js/
│   └── views/
├── routes/                 ✅ Web & API routes
│   ├── api.php
│   ├── console.php
│   └── web.php
├── storage/                ✅ (kosongkan isi subfolder)
│   ├── app/
│   ├── framework/
│   └── logs/
├── .env.example            ✅ PENTING! Template environment
├── .gitignore              ✅ Git configuration
├── artisan                 ✅ Laravel CLI
├── composer.json           ✅ PHP dependencies
├── composer.lock           ✅ Lock file
├── package.json            ✅ NPM dependencies
├── package-lock.json       ✅ NPM lock file
├── postcss.config.js       ✅ PostCSS config
├── tailwind.config.js      ✅ Tailwind config
├── vite.config.js          ✅ Vite config
├── phpunit.xml             ✅ Testing config
├── README.md               ✅ Dokumentasi project
├── INSTALASI.md            ✅ Panduan instalasi
├── DEPLOY_CHECKLIST.md     ✅ Quick checklist
└── PACKAGING_GUIDE.md      ✅ File ini
```

---

## ❌ File/Folder yang TIDAK Perlu Di-ZIP

### Auto-generated Folders (akan dibuat saat instalasi):

```
❌ /vendor/                  # Dibuat saat: composer install
❌ /node_modules/             # Dibuat saat: npm install
```

### Environment & Cache Files:

```
❌ .env                       # File konfigurasi pribadi (gunakan .env.example)
❌ /bootstrap/cache/*.php     # Cache files
❌ /storage/framework/cache/  # Cache Laravel
❌ /storage/framework/sessions/  # Session files
❌ /storage/framework/views/  # Compiled views
❌ /storage/logs/*.log        # Log files
```

### IDE & OS Files:

```
❌ .idea/                     # PHPStorm
❌ .vscode/                   # VSCode
❌ .DS_Store                  # macOS
❌ Thumbs.db                  # Windows
```

### Build Files:

```
❌ /public/build/             # Dibuat saat: npm run build
❌ /public/hot                # Vite HMR file
```

### Upload Files (jika ada):

```
❌ /storage/app/public/classes/*.jpg  # User uploads
❌ /public/storage/                   # Symbolic link
```

---

## 🚀 Cara Membuat ZIP

### Opsi 1: Manual (Windows Explorer)

1. Buka folder project di Windows Explorer
2. **Hapus folder berikut** (jika ada):
    - `vendor/`
    - `node_modules/`
    - `.env` (file, bukan .env.example)
    - `public/build/`
3. Select semua file dan folder yang tersisa
4. Klik kanan → **Send to** → **Compressed (zipped) folder**
5. Rename menjadi: `flexora-laravel.zip`

### Opsi 2: Menggunakan Command Line

```bash
# Pastikan Anda di folder project
cd D:\flexora-fixbgt\flexora-laravel

# Hapus folder yang tidak perlu (jika ada)
rmdir /s /q vendor
rmdir /s /q node_modules
rmdir /s /q public\build

# Buat ZIP (gunakan 7zip atau WinRAR)
# Atau gunakan Windows PowerShell:
Compress-Archive -Path * -DestinationPath ..\flexora-laravel.zip
```

### Opsi 3: Menggunakan Git (Recommended)

```bash
# Buat archive dari git (otomatis exclude file di .gitignore)
git archive -o ../flexora-laravel.zip HEAD

# Tambahkan file tambahan yang diperlukan
# (jika ada yang tidak di-track oleh git)
```

---

## 🔍 Verifikasi ZIP

Setelah membuat ZIP, extract ke folder temporary dan pastikan:

### Struktur Folder Lengkap:

```
✅ app/ folder ada
✅ config/ folder ada
✅ database/ folder ada
✅ public/ folder ada
✅ resources/ folder ada
✅ routes/ folder ada
✅ storage/ folder ada (boleh kosong)
```

### File Penting Ada:

```
✅ .env.example (BUKAN .env)
✅ composer.json
✅ package.json
✅ artisan
✅ README.md
✅ INSTALASI.md
✅ DEPLOY_CHECKLIST.md
```

### Folder Auto-Generated TIDAK Ada:

```
❌ vendor/ tidak ada
❌ node_modules/ tidak ada
❌ .env tidak ada
```

### Test Instalasi:

```bash
# Di folder hasil extract, coba jalankan:
composer install
# Jika berhasil, berarti struktur sudah benar
```

---

## 📊 Ukuran ZIP

-   **Tanpa vendor/ dan node_modules/**: ~5-10 MB ✅
-   **Dengan vendor/ dan node_modules/**: ~200-300 MB ❌ (TERLALU BESAR)

> **Catatan**: Folder `vendor/` dan `node_modules/` berisi ribuan file dependencies yang akan memperbesar ukuran ZIP. Folder ini akan otomatis dibuat saat menjalankan `composer install` dan `npm install`.

---

## 📝 Checklist Sebelum Submit

-   [ ] Folder `vendor/` sudah dihapus
-   [ ] Folder `node_modules/` sudah dihapus
-   [ ] File `.env` sudah dihapus (`.env.example` tetap ada)
-   [ ] Folder `public/build/` sudah dihapus
-   [ ] File `README.md` sudah update
-   [ ] File `INSTALASI.md` sudah ada
-   [ ] File `DEPLOY_CHECKLIST.md` sudah ada
-   [ ] Ukuran ZIP < 20 MB
-   [ ] Sudah test extract dan `composer install` berhasil

---

## 🎯 Apa yang Dosen Perlu Lakukan

Setelah menerima ZIP, dosen hanya perlu:

1. Extract ZIP
2. Install XAMPP, Composer, Node.js (jika belum)
3. Buat database `flexora_laravel`
4. Jalankan:
    ```bash
    composer install
    npm install
    copy .env.example .env
    php artisan key:generate
    php artisan jwt:secret
    php artisan migrate --seed
    npm run build
    php artisan serve
    ```
5. Akses: `http://localhost:8000`

**Admin login**: `admin@flexora.com` / `password`

Total waktu: **~15-20 menit**

---

## 💡 Tips

-   **Jangan submit folder vendor/** - Ini akan membuat ZIP sangat besar dan tidak perlu
-   **Pastikan .env.example ada** - Ini template untuk konfigurasi
-   **Test ZIP sebelum submit** - Extract di folder lain dan coba install
-   **Dokumentasi lengkap** - Dosen bisa mengikuti `INSTALASI.md`

---

**File ini hanya untuk referensi packaging. Tidak perlu disertakan dalam ZIP jika tidak diperlukan.**

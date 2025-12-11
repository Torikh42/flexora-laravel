# Flexora - Studio Fitness Booking & Membership System

Flexora adalah aplikasi web modern untuk manajemen reservasi kelas fitness dan sistem keanggotaan di studio fitness. Dibangun dengan Laravel 11, Blade templating, dan Tailwind CSS untuk memberikan pengalaman pengguna yang responsif dan intuitif.

---

## 📋 Deskripsi Project

Flexora adalah platform digital yang memungkinkan member studio fitness untuk:

-   **Booking kelas** sesuai jadwal yang tersedia
-   **Manajemen keanggotaan** dengan berbagai paket membership
-   **Tracking pemesanan** dengan status real-time (Pending/Confirmed)
-   **Dashboard personal** untuk melihat history kelas dan membership aktif
-   **Responsive design** yang bekerja di desktop, tablet, dan mobile

Platform ini dirancang untuk menghubungkan instruktur fitness dengan member melalui sistem booking yang efisien dan user-friendly.

---

## ✨ Fitur Utama

### 1. **Autentikasi & Manajemen User**

-   Registrasi akun baru dengan validasi email
-   Login/Logout dengan sistem JWT Token
-   Token disimpan di localStorage untuk sesi yang persisten
-   Profile management untuk update data user
-   Role-based access (Admin/Member)

### 2. **Admin Panel** 🆕

-   **Dashboard Admin**: Overview statistik users, classes, enrollments, dan memberships
-   **Manajemen Kelas (CRUD)**:
    -   Create, Read, Update, Delete kelas fitness
    -   Upload gambar kelas
    -   Manage deskripsi dan informasi kelas
-   **Manajemen Membership (CRUD)**:
    -   Create, Read, Update, Delete paket membership
    -   Set harga dan durasi membership
    -   Track jumlah user per membership
-   **Manajemen Jadwal (CRUD)** 🆕:
    -   Create, Read, Update, Delete jadwal kelas
    -   Set instruktur, waktu mulai & selesai
    -   Manage harga per sesi
    -   Link jadwal ke kelas tertentu
-   **Custom Pagination**: White theme dengan better spacing
-   **Sidebar Navigation**: Quick access ke semua fitur admin

### 3. **Kelas Fitness**

-   Daftar semua kelas yang tersedia (Yoga, Pilates, Pole Dance, dll)
-   Informasi detail: instruktur, jadwal, durasi, deskripsi kelas
-   Gambar kelas dengan storage system yang proper
-   Filter dan search kelas berdasarkan nama

### 4. **Sistem Booking Kelas**

-   **Booking dengan Membership**: Member dengan membership aktif bisa booking gratis
-   **Booking dengan Paid**: Dapat membayar per kelas tanpa membership
-   Status tracking: Pending (belum dikonfirmasi) / Confirmed (terkonfirmasi)
-   Batasan kuota: Setiap kelas punya batasan jumlah peserta
-   Notifikasi status perubahan

### 5. **Membership**

-   Berbagai paket membership (1 bulan, 3 bulan, 6 bulan, 1 tahun)
-   Harga berbeda untuk setiap paket
-   Tracking tanggal aktif & berakhir membership
-   Countdown hari tersisa untuk membership yang aktif
-   **Alur Pembelian Membership**: Pengguna diarahkan ke halaman pembayaran khusus setelah memilih paket membership, sebelum membership diaktifkan.

### 6. **Dashboard Personal**

-   **Enrollment Cards**: Tampilkan semua kelas yang sudah di-booking
    -   Gambar kelas dengan gradient fallback
    -   Status badge (Membership/Confirmed/Pending)
    -   Informasi instruktur, tanggal, waktu
-   **Membership Cards**: Daftar membership aktif user
    -   Nama membership
    -   Tanggal mulai & berakhir
    -   Counter hari tersisa
-   **Filter & Search**:
    -   Filter berdasarkan status (All/Confirmed/Pending)
    -   Search by class name
    -   Live filtering dengan update count

### 7. **Contact & Support**

-   Halaman contact dengan informasi studio
-   Detail: Alamat, Telepon, Email, Jam Operasional
-   Form kontak (dummy) untuk komunikasi dengan admin

### 8. **Navbar & Navigation**

-   Responsive navigation bar dengan hamburger menu
-   Dynamic auth section (login/logout)
-   Tampil user info ketika sudah login
-   Navigation: Home, Classes, Dashboard, Contact

---

## 🛠️ Tech Stack

### Backend

-   **Framework**: Laravel 11
-   **Language**: PHP 8.3
-   **Authentication**: JWT (Tymon/JWTAuth)
-   **Database**: MySQL 8.0
-   **ORM**: Eloquent

### Frontend

-   **Templating**: Blade (Laravel)
-   **Styling**: Tailwind CSS
-   **JavaScript**: Vanilla JS (no framework)
-   **Build Tool**: Vite
-   **API Client**: Fetch API

### Database

-   **DBMS**: MySQL
-   **Migration**: Laravel Migrations
-   **Seeding**: Laravel Seeders untuk dummy data

---

## 📊 Database Schema

### Models & Relationships

```
User (Tabel Users)
├── hasMany() Enrollments
├── hasMany() UserMemberships
├── Fields: name, email, password, role (admin/member)
└── Relationships untuk tracking pemesanan & membership

StudioClass (Tabel studio_classes)
├── hasMany() Schedules
├── Fields: name, slug, image, description

Schedule (Tabel schedules) 🆕
├── belongsTo() StudioClass
├── hasMany() Enrollments
├── Fields: studio_class_id, instructor, start_time, end_time, price
├── Casts: start_time (datetime), end_time (datetime)

Enrollment (Tabel enrollments)
├── belongsTo() User
├── belongsTo() Schedule
├── Fields: status (pending/confirmed), enrollment_type (free_membership/paid), created_at

Membership (Tabel memberships)
├── hasMany() UserMemberships
├── Fields: name, price, duration_days, description

UserMembership (Tabel user_memberships)
├── belongsTo() User
├── belongsTo() Membership
├── Fields: start_date, end_date, status
```

---

## 🚀 Instalasi & Setup

### Prerequisites

-   PHP 8.3+
-   Composer
-   Node.js 18+
-   MySQL 8.0
-   Git

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd flexora-laravel
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Setup Environment**

    ```bash
    cp .env.example .env
    ```

4. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5. **Setup Database**

    ```bash
    # Create database
    mysql -u root -p -e "CREATE DATABASE flexora_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

    # Run migrations
    php artisan migrate

    # Seed dummy data
    php artisan migrate:seed
    ```

6. **Generate JWT Secret**

    ```bash
    php artisan jwt:secret
    ```

7. **Build Assets**

    ```bash
    npm run build
    # atau untuk development
    npm run dev
    ```

8. **Start Development Server**

    ```bash
    php artisan serve --host=127.0.0.1 --port=8000
    ```

9. **Akses Aplikasi**
    - Open http://localhost:8000 di browser

---

## 📝 API Endpoints

### Authentication

| Method | Endpoint                 | Description              |
| ------ | ------------------------ | ------------------------ |
| POST   | `/api/auth/login`        | Login user               |
| POST   | `/api/auth/logout`       | Logout user              |
| GET    | `/api/auth/user-profile` | Get current user profile |

### Classes & Schedules

| Method | Endpoint                         | Description            |
| ------ | -------------------------------- | ---------------------- |
| GET    | `/api/studio-classes`            | Get all studio classes |
| GET    | `/api/classes/available-by-date` | Get schedules by date  |

### Enrollments

| Method | Endpoint           | Description                             |
| ------ | ------------------ | --------------------------------------- |
| GET    | `/api/enrollments` | Get user's enrollments with memberships |
| POST   | `/api/enrollments` | Create new enrollment                   |

### Memberships

| Method | Endpoint                         | Description         |
| ------ | -------------------------------- | ------------------- |
| GET    | `/api/memberships`               | Get all memberships |
| POST   | `/api/memberships/{id}/purchase` | Purchase membership |

---

## 🌐 Routes

### Web Routes (Blade Views)

| Method | Route                               | View                     | Description                         |
| ------ | ----------------------------------- | ------------------------ | ----------------------------------- |
| GET    | `/`                                 | home.blade.php           | Home page                           |
| GET    | `/login`                            | login.blade.php          | Login page                          |
| GET    | `/signup`                           | signup.blade.php         | Registration page                   |
| GET    | `/dashboard`                        | dashboard.blade.php      | User dashboard                      |
| GET    | `/contact`                          | contact.blade.php        | Contact page                        |
| GET    | `/studio_classes`                   | classes/index.blade.php  | List all classes                    |
| GET    | `/memberships/{membership}/payment` | pay_membership.blade.php | Halaman pembayaran paket membership |

---

## 🎨 UI/UX Features

### Design System

-   **Color Scheme**:

    -   Primary: Amber/Brown (#5a4636, #7a6047)
    -   Gradient backgrounds untuk visual appeal
    -   White cards dengan shadow untuk depth

-   **Typography**:
    -   Font family: Segoe UI, Tailwind default
    -   Responsive font sizes untuk berbagai devices

### Responsive Design

-   **Mobile First**: Dioptimalkan untuk mobile (< 640px)
-   **Breakpoints**:
    -   sm: 640px (tablet)
    -   md: 768px (small desktop)
    -   lg: 1024px (desktop)
    -   xl: 1280px (large desktop)

### Interactive Components

-   Loading spinners dengan animation
-   Modal dialog untuk detail view
-   Hover effects pada cards
-   Filter buttons dengan state management
-   Search dengan live filtering

---

## 🔐 Keamanan & Autentikasi

### Sistem Autentikasi Dual

Flexora menggunakan **dua metode autentikasi** untuk kebutuhan yang berbeda:

#### 1. **JWT (JSON Web Token)** - API & Member Area

-   **Digunakan untuk**: API endpoints (`/api/*`) dan member frontend
-   **Package**: `tymon/jwt-auth`
-   **Flow**:
    -   User login/register via API (`/api/auth/login`)
    -   Token JWT dikirim ke frontend
    -   Token disimpan di `localStorage`
    -   Setiap request API menyertakan token di header: `Authorization: Bearer {token}`
-   **Kegunaan**:
    -   User enrollment/booking
    -   Membership management
    -   User dashboard data
    -   Profile management
-   **Keuntungan**: Stateless, cocok untuk SPA, mobile apps, dan API consumption

#### 2. **Session-based Authentication** - Admin Panel

-   **Digunakan untuk**: Admin panel (`/admin/*`)
-   **Mechanism**: Laravel native session & cookies
-   **Flow**:
    -   Admin login via web form (`/web-login`)
    -   Credentials dicek dengan `Auth::attempt()`
    -   Session dibuat dan cookie dikirim
    -   Middleware `AdminMiddleware` memvalidasi role admin
-   **Kegunaan**:
    -   Admin dashboard
    -   CRUD operations (classes, memberships, schedules)
    -   Admin-only features
-   **Keuntungan**: Traditional web auth, lebih aman untuk sensitive operations, built-in CSRF protection

#### Credentials Default

**Admin Account** (untuk testing):

```
Email: admin@flexora.com
Password: password
Role: admin
```

### Fitur Keamanan Lainnya

-   **CORS**: Dikonfigurasi untuk allow requests dari frontend
-   **Validation**: Form validation di backend & frontend untuk semua input
-   **Database Protection**: Query protection dengan Eloquent ORM untuk mencegah SQL injection
-   **CSRF Protection**: Otomatis aktif untuk semua POST/PUT/DELETE requests di web routes
-   **Password Hashing**: Menggunakan bcrypt untuk semua password
-   **Role-based Access**: Middleware untuk membatasi akses admin panel
-   **HTTPS Ready**: Environment variables siap untuk production deployment

---

## 📈 Performance Optimization

-   **Eager Loading**: Relationship queries menggunakan `with()` untuk menghindari N+1 problem
-   **API Optimization**: Endpoint userProfile hanya return basic user info
-   **Navbar Caching**: Auth check dengan 5-second timeout untuk responsiveness
-   **Lazy Loading**: Assets di-load secara optimal

---

## 🐛 Troubleshooting

### Database Connection Error

```bash
# Clear cache dan restart
php artisan optimize:clear
php artisan migrate:refresh --seed
```

### JWT Token Issues

```bash
# Regenerate JWT secret
php artisan jwt:secret
```

### Asset Not Loading

```bash
# Rebuild assets
npm run build
```

### Slow Performance

-   Check MySQL connection
-   Clear Laravel cache: `php artisan optimize:clear`
-   Verify database indexes
-   Check API response time di browser DevTools

---

## 📚 Struktur File

```
flexora-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/  🆕
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminClassController.php
│   │   │   ├── AdminMembershipController.php
│   │   │   └── AdminScheduleController.php  🆕
│   │   ├── AuthController.php
│   │   ├── StudioClassController.php
│   │   ├── EnrollmentController.php
│   │   ├── DashboardController.php
│   │   ├── ContactController.php
│   │   ├── MembershipController.php
│   │   └── PaymentController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php  🆕
│   ├── Models/
│   │   ├── User.php
│   │   ├── StudioClass.php
│   │   ├── Schedule.php
│   │   ├── Enrollment.php
│   │   ├── Membership.php
│   │   └── UserMembership.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── AdminUserSeeder.php  🆕
│       └── ... other seeders
├── resources/
│   ├── views/
│   │   ├── admin/  🆕
│   │   │   ├── dashboard/
│   │   │   ├── classes/
│   │   │   ├── memberships/
│   │   │   └── schedules/  🆕
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── admin.blade.php  🆕
│   │   ├── vendor/
│   │   │   └── pagination/  🆕
│   │   ├── dashboard.blade.php
│   │   ├── contact.blade.php
│   │   └── ... other views
│   ├── css/
│   └── js/
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   └── app/
│       └── public/
│           └── classes/  🆕
└── public/
    ├── images/
    └── storage/
```

---

## 📦 Panduan Instalasi untuk Pengumpulan Project

> **Untuk Dosen/Reviewer**: Jika Anda ingin menjalankan proyek ini di komputer Anda, silakan ikuti panduan berikut:

### 📖 Dokumentasi Instalasi

1. **[INSTALASI.md](./INSTALASI.md)** - Panduan lengkap step-by-step dengan penjelasan detail

    - Requirements (software yang dibutuhkan)
    - Langkah instalasi dari awal
    - Konfigurasi environment
    - Troubleshooting lengkap

2. **[DEPLOY_CHECKLIST.md](./DEPLOY_CHECKLIST.md)** - Quick checklist untuk deployment cepat
    - Checklist prerequisites
    - Langkah cepat (15 menit)
    - Verifikasi instalasi

### ⚡ Quick Start

```bash
# 1. Install: XAMPP, Composer, Node.js
# 2. Buat database: flexora_laravel
# 3. Extract project dan jalankan:

composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

**Admin Access**:

-   URL: `http://localhost:8000/admin/dashboard`
-   Email: `admin@flexora.com`
-   Password: `password`

### 📋 Yang Dibutuhkan

-   **XAMPP** (PHP 8.2+, MySQL 8.0) - [Download](https://www.apachefriends.org/)
-   **Composer** - [Download](https://getcomposer.org/)
-   **Node.js** (v18+) - [Download](https://nodejs.org/)

**Estimasi waktu setup**: 15-20 menit

---

## 🚀 Deployment

### Production Checklist

-   [ ] Set `.env` dengan production values
-   [ ] Generate JWT secret
-   [ ] Run migrations
-   [ ] Build assets: `npm run build`
-   [ ] Set permissions: `chmod -R 775 storage/ bootstrap/cache`
-   [ ] Configure web server (Apache/Nginx)
-   [ ] Setup SSL certificate
-   [ ] Configure database backups

### Hosting Recommendations

-   **Server**: Apache/Nginx
-   **PHP**: 8.3+
-   **Database**: MySQL 8.0+
-   **Storage**: Min 5GB

---

## 👥 Tim Development

Project ini dikembangkan sebagai solusi booking kelas fitness yang komprehensif dan user-friendly.

---

## 📄 License

Project ini dilisensikan di bawah MIT License - lihat file LICENSE untuk detail.

---

## 📞 Support & Contact

Untuk pertanyaan atau support:

-   Email: support@flexora.com
-   Alamat: Jl. Fitness Sehat No. 123, Jakarta Pusat 12860, Indonesia
-   Telepon: +62 812-3456-7890

---

**Version**: 1.2.0  
**Last Updated**: December 2025

**Recent Updates**:

-   ✅ Admin Panel dengan CRUD lengkap
-   ✅ Schedule Management System
-   ✅ Image Storage Migration
-   ✅ Custom Pagination Design
-   ✅ Role-based Access Control

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

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

### 2. **Kelas Fitness**

-   Daftar semua kelas yang tersedia (Yoga, Pilates, Pole Dance, dll)
-   Informasi detail: instruktur, jadwal, durasi, deskripsi kelas
-   Gambar kelas dengan fallback emoji
-   Filter dan search kelas berdasarkan nama

### 3. **Sistem Booking Kelas**

-   **Booking dengan Membership**: Member dengan membership aktif bisa booking gratis
-   **Booking dengan Paid**: Dapat membayar per kelas tanpa membership
-   Status tracking: Pending (belum dikonfirmasi) / Confirmed (terkonfirmasi)
-   Batasan kuota: Setiap kelas punya batasan jumlah peserta
-   Notifikasi status perubahan

### 4. **Membership**

-   Berbagai paket membership (1 bulan, 3 bulan, 6 bulan, 1 tahun)
-   Harga berbeda untuk setiap paket
-   Tracking tanggal aktif & berakhir membership
-   Countdown hari tersisa untuk membership yang aktif
-   **Alur Pembelian Membership**: Pengguna diarahkan ke halaman pembayaran khusus setelah memilih paket membership, sebelum membership diaktifkan.

### 5. **Dashboard Personal**

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

### 6. **Contact & Support**

-   Halaman contact dengan informasi studio
-   Detail: Alamat, Telepon, Email, Jam Operasional
-   Form kontak (dummy) untuk komunikasi dengan admin

### 7. **Navbar & Navigation**

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
└── Relationships untuk tracking pemesanan & membership

StudioClass (Tabel studio_classes)
├── hasMany() Schedules
├── Fields: name, slug, image, description

Schedule (Tabel schedules)
├── belongsTo() StudioClass
├── hasMany() Enrollments
├── Fields: start_time, end_time, capacity, booked_count

Enrollment (Tabel enrollments)
├── belongsTo() User
├── belongsTo() Schedule
├── Fields: status (pending/confirmed), enrollment_type (free_membership/paid), created_at

Membership (Tabel memberships)
├── hasMany() UserMemberships
├── Fields: name, price, duration_days

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

| Method | Route             | View                    | Description       |
| ------ | ----------------- | ----------------------- | ----------------- |
| GET    | `/`               | home.blade.php          | Home page         |
| GET    | `/login`          | login.blade.php         | Login page        |
| GET    | `/signup`         | signup.blade.php        | Registration page |
| GET    | `/dashboard`      | dashboard.blade.php     | User dashboard    |
| GET    | `/contact`        | contact.blade.php       | Contact page      |
| GET    | `/studio_classes` | classes/index.blade.php | List all classes  |
| GET    | `/memberships/{membership}/payment` | pay_membership.blade.php | Halaman pembayaran paket membership  |

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

## 🔐 Keamanan

-   **JWT Authentication**: Token-based authentication untuk API
-   **CORS**: Diatur untuk allow requests dari frontend
-   **Validation**: Form validation di backend & frontend
-   **Database**: Query protection dengan Eloquent ORM
-   **HTTPS Ready**: Environment variables untuk production

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
│   │   ├── AuthController.php
│   │   ├── StudioClassController.php
│   │   ├── EnrollmentController.php
│   │   ├── DashboardController.php
│   │   ├── ContactController.php
│   │   └── MembershipController.php
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
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── components/
│   │   ├── dashboard.blade.php
│   │   ├── contact.blade.php
│   │   └── ... other views
│   ├── css/
│   └── js/
├── routes/
│   ├── api.php
│   └── web.php
└── public/
    ├── images/
    └── storage/
```

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

**Version**: 1.0.0  
**Last Updated**: November 2025

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

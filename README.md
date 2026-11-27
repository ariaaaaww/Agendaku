 # 📅 Agendaku — Sistem Manajemen Agenda & Acara Sekolah Multi-Role

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-5.x-F25F4C?style=for-the-badge)

> **Agendaku** adalah aplikasi web terpadu berbasis **Laravel 13** untuk pengelolaan agenda belajar, daftar tugas (_to-do list_), pengumuman sekolah, kalender akademik, serta koordinasi acara kesiswaan dengan kontrol akses berbasis peran (**Role-Based Access Control / Multi-Role**).

---

## 📖 Daftar Isi

1. [Informasi Proyek](#-1-informasi-proyek)
2. [Instalasi](#-2-instalasi)
3. [Panduan Penggunaan](#-3-panduan-penggunaan)
4. [Arsitektur Proyek](#-4-arsitektur-proyek)
5. [Teknologi yang Digunakan](#-5-teknologi-yang-digunakan)
6. [Pengujian (Testing)](#-6-pengujian-testing)
7. [Kontribusi](#-7-kontribusi)
8. [Lisensi](#-8-lisensi)

---

## 📌 1. Informasi Proyek

### Deskripsi Singkat
**Agendaku** merupakan platform sentralisasi jadwal sekolah yang menghubungkan Siswa, Guru, Pengurus OSIS, dan Administrator dalam satu sistem basis data _real-time_.

### Tujuan & Permasalahan yang Diselesaikan
Di lingkungan sekolah, informasi tugas dari guru, jadwal kegiatan OSIS, serta catatan tenggat waktu pribadi siswa sering kali tersebar di berbagai saluran komunikasi. Hal ini menyebabkan:
- Siswa kesulitan memantau prioritas dan tenggat waktu (_deadline_) tugas secara terstruktur.
- Acara sekolah/OSIS kurang tersosialisasi ke kalender harian seluruh warga sekolah.
- Pengumuman sekolah tidak memiliki tempat terpusat yang langsung terlihat saat siswa membuka agenda harian.

**Agendaku** menyelesaikan masalah tersebut dengan menyatukan kalender akademik, distribusi tugas massal oleh guru, pengumuman aktif, dan publikasi acara OSIS ke dalam satu dasbor terintegrasi.

### Target Pengguna (4 Role)
1. **Student (Siswa):** Mengelola tugas pribadi, memantau tugas akademik dari guru, melihat pengumuman aktif, menerima pengingat tenggat waktu, serta melihat kalender acara sekolah.
2. **Teacher (Guru):** Menerbitkan tugas massal ke seluruh siswa, mengelola tugas & acara sistem, serta menerbitkan atau menghapus pengumuman sekolah.
3. **Student Council (Pengurus OSIS):** Mengelola publikasi, pembaruan, dan penghapusan acara sekolah/kesiswaan.
4. **Admin (Administrator):** Memantau statistik sistem, progres tugas per siswa, seluruh acara sekolah, mengelola akun pengguna (CRUD), serta memiliki akses penuh ke seluruh panel role.

### Fitur Utama yang Tersedia
- **Autentikasi & Otorisasi Berbasis Role:** Login, registrasi siswa (role otomatis dikunci sebagai `student`), logout, serta middleware `CheckByRole` untuk membatasi akses halaman berdasarkan peran.
- **Kalender Sekolah Terintegrasi & JSON API:** Kalender bulanan interaktif (navigasi bulan/tahun) yang menggabungkan tugas kategori `sekolah`, tugas pribadi pengguna, dan `school_events` (acara OSIS), dilengkapi endpoint `GET /api/calendar/month`.
- **Manajemen Tugas & Alert Otomatis (Siswa):** Daftar tugas belum selesai (_To-Do List_), checklist status selesai (`PATCH /tasks/{task}/toggle`), CRUD tugas mandiri, serta banner peringatan otomatis untuk tugas yang melewati tenggat (_overdue_), jatuh tempo hari ini, atau esok hari.
- **Panel Guru:** Distribusi tugas massal ke seluruh akun siswa sekaligus, pembuatan acara sekolah, serta pengelolaan pengumuman aktif (pengumuman baru otomatis menonaktifkan pengumuman lama, lengkap dengan fitur hapus pengumuman).
- **Panel OSIS:** CRUD acara sekolah pada tabel `school_events` yang langsung tampil pada kalender seluruh role.
- **Panel Admin:** Statistik sistem aktual dari database, tabel progres siswa, pemantauan acara sekolah, serta CRUD akun pengguna via modal AJAX (dilengkapi proteksi agar admin tidak dapat menghapus akunnya sendiri).

---

## 🚀 2. Instalasi

### Persyaratan Sistem
Pastikan perangkat Anda telah terpasang software berikut:
- **PHP** `^8.3` (pengembangan diuji pada PHP 8.5) dengan ekstensi standar Laravel (`pdo_mysql` / `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`).
- **Composer** `2.x`
- **Node.js** `18.x` atau lebih baru & **NPM**
- **Database:** **MySQL** (misal melalui Laragon / XAMPP) atau **SQLite**
- **Git**

### Langkah-Langkah Instalasi

#### 1. Clone Repository
```bash
git clone https://github.com/ariaaaaww/Agendaku.git
cd Agendaku
```
*(Sesuaikan URL repository di atas apabila Anda meng-clone dari fork/remote lain).*

#### 2. Instalasi Dependency Backend & Frontend
```bash
composer install
npm install
```

#### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:

- **Windows (PowerShell / CMD):**
  ```powershell
  copy .env.example .env
  ```
- **Linux / macOS:**
  ```bash
  cp .env.example .env
  ```

Lalu buat *Application Key*:
```bash
php artisan key:generate
```

#### 4. Konfigurasi Database
Secara bawaan, `.env.example` menggunakan **SQLite**. Anda dapat memilih salah satu opsi berikut:

- **Opsi A — Menggunakan MySQL (Disarankan untuk lingkungan Laragon/XAMPP):**
  Buat database kosong di MySQL (contoh: `agendaku_db`), lalu ubah bagian database di file `.env`:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=agendaku_db
  DB_USERNAME=root
  DB_PASSWORD=
  ```

- **Opsi B — Menggunakan SQLite (Default `.env.example`):**
  Pastikan file `database/database.sqlite` tersedia:
  ```bash
  # Windows PowerShell
  New-Item -ItemType File -Path database/database.sqlite -Force
  ```

#### 5. Jalankan Migration & Seeder Akun Awal
Jalankan migrasi untuk membentuk tabel `users`, `tasks`, `school_events`, `announcements`, serta tabel sistem Laravel (`sessions`, `cache`, `jobs`):
```bash
php artisan migrate
```

Untuk mengisi akun uji coba per role (Admin, Guru, OSIS, Siswa), jalankan `UserSeeder`:
```bash
php artisan db:seed --class=UserSeeder
```
> **Catatan:** Jalankan `UserSeeder` secara spesifik (`--class=UserSeeder`) dan hindari `db:seed` tanpa parameter apabila `DatabaseSeeder` masih memanggil `User::factory()` yang belum disesuaikan setelah penghapusan kolom `email`.

#### 6. Build Asset & Jalankan Aplikasi
Anda dapat menjalankan server Laravel dan Vite secara bersamaan menggunakan script bawaan Composer:
```bash
composer run dev
```
Atau jalankan secara manual di dua terminal terpisah:
```bash
# Terminal 1: Server Laravel
php artisan serve

# Terminal 2: Asset Bundler Vite
npm run dev
```
Aplikasi dapat diakses melalui browser di: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### Mengatasi Kesalahan Instalasi Umum
1. **Error `ViteException: Unable to locate file in Vite manifest`:**
   Pastikan `npm run dev` sedang berjalan, atau jalankan `npm run build` untuk menghasilkan file manifest produksi di `public/build`.
2. **Error `SQLSTATE[HY000] [1049] Unknown database`:**
   Pastikan database yang tertulis pada `DB_DATABASE` di `.env` sudah dibuat di MySQL sebelum menjalankan `php artisan migrate`.
3. **Error `php` tidak dikenali di PowerShell Windows:**
   Tambahkan path binary PHP (misal `D:\laragon2\bin\php\php-8.x`) ke Environment Variable `PATH` sistem Anda.

---

## 💡 3. Panduan Penggunaan

### Akun Uji Coba Bawaan (`UserSeeder`)
Setelah menjalankan `php artisan db:seed --class=UserSeeder`, gunakan kredensial berikut pada halaman `/login`:

| Role | Nama | Username | Password | Dashboard Tujuan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Admin User | `Admin` | `admin123` | `/admin` |
| **Guru** | Teacher User | `Guru` | `12345678` | `/teacher` |
| **OSIS** | Council User | `Osis` | `12345678` | `/council` |
| **Siswa** | Student User | `Siswa` | `12345678` | `/students` |

### Alur Penggunaan Berdasarkan Role

#### 1. Guest (Belum Login)
- Mengakses `/` akan otomatis diarahkan ke `/login`.
- Pengguna baru dapat mendaftar melalui `/register` (akun baru otomatis mendapatkan role **Siswa**).

#### 2. Student (Siswa) — `/students`
- **Kalender:** Melihat agenda tugas sekolah, tugas pribadi, dan acara OSIS per tanggal, serta berpindah bulan menggunakan tombol navigasi `◀`, `Hari Ini`, `▶`.
- **To-Do List:** Melihat daftar tugas belum selesai urut dari tenggat terdekat, menandai tugas selesai melalui tombol checklist, serta mengedit atau menghapus tugas mandiri.
- **Notifikasi & Pengumuman:** Banner atas menampilkan pengumuman aktif terbaru dari guru dan status tenggat waktu (terlewat, hari ini, esok hari).
- **Acara Sekolah & Tambah Agenda:** Melihat daftar acara sekolah/OSIS dan menambahkan agenda pribadi/sekolah baru melalui tab formulir.

#### 3. Teacher (Guru) — `/teacher`
- **Pengumuman Sekolah:** Mengirim pengumuman baru melalui formulir (otomatis menggantikan pengumuman aktif sebelumnya) dan menghapus pengumuman aktif pada kartu **Kelola Tugas & Acara Sistem**.
- **Tambah Tugas/Acara Massal:**
  - Memilih kategori **Tugas Akademik (`sekolah`)** akan membagikan tugas ke seluruh akun siswa.
  - Memilih kategori **Acara / Ujian Sekolah (`acara`)** akan menyimpan agenda ke tabel `school_events`.
- **Kelola Tugas & Acara Sistem:** Mengubah atau menghapus tugas yang terdaftar pada sistem.

#### 4. Student Council (OSIS) — `/council`
- **Kelola Acara OSIS:** Menambahkan acara baru (`POST /school-events`), mengubah judul/tanggal acara lewat modal, dan menghapus acara. Semua acara tersimpan di tabel `school_events` dan otomatis tampil di kalender semua role.

#### 5. Admin — `/admin`
- **Statistik & Monitoring:** Melihat jumlah total pengguna, total tugas selesai, total tugas pending, progres tugas per siswa, serta daftar acara sekolah/OSIS.
- **Manajemen Akun Pengguna:** Menambah akun baru (role bebas), memperbarui profil/role/password pengguna, dan menghapus akun pengguna lain. Sistem memblokir admin untuk menghapus akunnya sendiri (`HTTP 403`).
- **Akses Lintas Panel:** Melalui sidebar, Admin dapat mengakses halaman Siswa, Guru, dan OSIS secara langsung.

### Endpoint API Kalender
- **Endpoint:** `GET /api/calendar/month`
- **Parameter Opsional:** `?year=2026&month=11`
- **Contoh Respons JSON:**
  ```json
  {
    "year": 2026,
    "month": 11,
    "month_name": "November",
    "first_day_of_week": 0,
    "days_in_month": 30,
    "today_date": "2026-11-27",
    "agendas": {
      "2026-11-20": [
        { "id": 1, "title": "Tugas Matematika", "category": "sekolah", "date": "2026-11-20" },
        { "id": 2, "title": "Pentas Seni", "category": "acara", "date": "2026-11-20" }
      ]
    }
  }
  ```

---

## 🏗️ 4. Arsitektur Proyek

### Struktur Direktori Proyek
```plaintext
Agendaku/
├── app/
│   ├── Enums/
│   │   └── UserRole.php              # Enum role: admin, student, teacher, council
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php        # Base controller dengan helper getCalendarData()
│   │   │   ├── AuthController.php    # Login, register, redirect role, & logout
│   │   │   ├── AdminController.php   # Statistik admin & CRUD pengguna (JSON)
│   │   │   ├── TeacherController.php # Panel guru & distribusi tugas massal
│   │   │   ├── CouncilController.php # Panel OSIS & daftar acara sekolah
│   │   │   ├── StudentController.php # Dashboard siswa, to-do list, & alert deadline
│   │   │   ├── TaskController.php    # CRUD tugas & toggle status selesai
│   │   │   ├── SchoolEventController.php  # CRUD acara OSIS/sekolah
│   │   │   ├── AnnouncementController.php # Publikasi & hapus pengumuman sekolah
│   │   │   └── CalendarController.php     # API JSON /api/calendar/month
│   │   └── Middleware/
│   │       └── CheckByRole.php       # Middleware otorisasi role (alias: 'role')
│   └── Models/
│       ├── User.php                  # Relasi tasks, createdTasks, events, announcements
│       ├── Task.php                  # Model tugas (milik user & pembuat/creator)
│       ├── SchoolEvent.php           # Model acara sekolah (relasi organizer)
│       └── Announcement.php          # Model pengumuman (relasi author)
├── bootstrap/
│   └── app.php                       # Registrasi routing, alias middleware 'role', & JSON exception
├── database/
│   ├── migrations/                   # Skema tabel users, tasks, school_events, announcements
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php            # Seeder akun default per role
├── resources/
│   ├── css/app.css                   # Konfigurasi Tailwind CSS v4 & custom theme
│   ├── js/app.js                     # Navigasi tab dashboard & menu sidebar mobile
│   └── views/
│       ├── admin/index.blade.php     # Antarmuka Panel Admin & modal CRUD user
│       ├── auth/                     # Halaman login.blade.php & register.blade.php
│       ├── components/
│       │   ├── sidebar.blade.php     # Navigasi sidebar adaptif berdasarkan role
│       │   └── dashboard-views.blade.php # Komponen bersama: banner, kalender, to-do, acara, tambah tugas
│       ├── council/index.blade.php   # Antarmuka Panel OSIS
│       ├── layouts/app.blade.php     # Master layout HTML & Vite assets
│       ├── students/index.blade.php  # Antarmuka Dashboard Siswa
│       └── teachers/index.blade.php  # Antarmuka Panel Guru
├── routes/
│   └── web.php                       # Definisi seluruh rute web, role middleware, & API kalender
├── tests/
│   ├── Feature/
│   │   ├── ExampleTest.php
│   │   └── SystemAuditTest.php       # Suite pengujian Pest menyeluruh lintas role
│   └── Unit/ExampleTest.php
├── composer.json
├── package.json
├── phpunit.xml                       # Konfigurasi testing (SQLite :memory:)
└── vite.config.js
```

### Alur Kerja Sistem (MVC & Relasi Data)
1. **Request & Routing (`routes/web.php`):** Setiap permintaan HTTP melewati middleware `auth` dan `role:<peran>` (didaftarkan di `bootstrap/app.php` menggunakan `CheckByRole`). Admin secara otomatis lolos seluruh pengecekan role.
2. **Controller & Data Kalender (`Controller.php`):** Seluruh controller dashboard (`StudentController`, `TeacherController`, `CouncilController`, `AdminController`) mewarisi helper `getCalendarData()` untuk menggabungkan data `Task` dan `SchoolEvent` per tanggal tanpa duplikasi kode.
3. **Database & Relasi Model:**
   - `User` `hasMany` `Task` (`user_id` sebagai penerima dan `created_by_id` sebagai pembuat), `hasMany` `SchoolEvent`, dan `hasMany` `Announcement`.
   - `Task`, `SchoolEvent`, dan `Announcement` masing-masing terhubung kembali ke `User` via `belongsTo`.
4. **Frontend (Blade + Tailwind + JS):** Blade merender komponen bersama `components/dashboard-views.blade.php` untuk kalender, pengumuman, dan daftar tugas, sementara `resources/js/app.js` menangani perpindahan tab dan responsivitas sidebar. Operasi CRUD Panel Admin menggunakan `fetch()` JSON dengan proteksi `X-CSRF-TOKEN`.

---

## 🧪 5. Teknologi yang Digunakan

### Backend & Framework
- **PHP (`^8.3`):** Bahasa pemrograman sisi server (menggunakan fitur modern seperti Backed Enum pada `App\Enums\UserRole`).
- **Laravel Framework (`^13.17`):** Framework utama untuk routing, middleware, validasi, session, dan Eloquent ORM.
- **Laravel Tinker (`^3.0`):** REPL interaktif untuk debugging dan inspeksi data aplikasi.

### Frontend & Asset Bundling
- **Laravel Blade:** Template engine server-side untuk merender antarmuka HTML dinamis.
- **Tailwind CSS (`^4.0.0`) & `@tailwindcss/vite`:** Framework CSS utility-first untuk styling responsif dan tema antarmuka.
- **Vite (`^8.0.0`) & `laravel-vite-plugin` (`^3.1`):** Asset bundler untuk kompilasi CSS dan JavaScript.
- **Iconify (`@iconify/tailwind4`, `@iconify-json/heroicons`, `@iconify/json`):** Penyedia ikon UI langsung melalui class Tailwind.
- **Vanilla JavaScript (ES6+):** Mengatur perpindahan tab UI, modal edit/tambah, serta request AJAX (`fetch`) pada panel admin.

### Database
- **MySQL / SQLite:** Basis data relasional (menggunakan migrasi standar Laravel dan diuji dengan SQLite `:memory:` pada test suite).

### Tools Pengembangan & Pengujian
- **Pest PHP (`^5.1`) & `pest-plugin-laravel` (`^5.0`):** Framework pengujian otomatis untuk feature dan unit test.
- **Laravel Pint (`^1.27`):** Code style fixer otomatis berstandar PSR-12.
- **Laravel Pail (`^1.2.5`) & Collision (`^8.6`):** Pemantauan log dan pelaporan error CLI yang informatif.
- **Concurrently (`^10.0.3`):** Menjalankan server Laravel, queue listener, dan Vite secara paralel melalui `composer run dev`.

---

## ✅ 6. Pengujian (Testing)

Proyek ini menggunakan **Pest PHP** dengan konfigurasi basis data SQLite `:memory:` pada `phpunit.xml`, sehingga pengujian tidak memodifikasi data pada database MySQL lokal Anda.

Jalankan seluruh test suite dengan perintah:
```bash
php artisan test --compact
```
Atau jalankan khusus suite pengujian fitur lintas role:
```bash
php artisan test --compact tests/Feature/SystemAuditTest.php
```

> **Catatan Audit Pengujian:** Pada implementasi saat ini, `AuthController::registerPost()` masih menyertakan field `'email'` saat `User::create()`, sementara kolom `email` sudah dihapus oleh migration `2026_11_12_031307_drop_email_from_users_table.php`. Jika Anda menjalankan pengujian registrasi (`TC-008`), pastikan kolom `'email'` sudah dibersihkan dari `AuthController.php` dan `User.php`. *(Perlu dikonfirmasi kembali oleh developer sebelum rilis produksi).*

---

## 🤝 7. Kontribusi

Kontribusi sangat terbuka untuk pengembangan fitur maupun perbaikan bug. Ikuti alur kerja berikut agar kode tetap rapi dan konsisten:

1. **Fork Repository** ini ke akun GitHub Anda, lalu clone hasil fork ke komputer lokal.
2. **Buat Branch Baru** dari branch utama dengan penamaan yang deskriptif:
   - `feat/<nama-fitur>` untuk fitur baru (contoh: `feat/export-agenda-pdf`)
   - `fix/<nama-perbaikan>` untuk perbaikan bug (contoh: `fix/register-drop-email`)
   - `docs/<topik>` untuk pembaruan dokumentasi
3. **Terapkan Perubahan Kode** sesuai arsitektur MVC Laravel proyek ini:
   - Gunakan `App\Enums\UserRole` untuk pengecekan role.
   - Rapikan format kode PHP menggunakan Laravel Pint sebelum commit:
     ```bash
     vendor/bin/pint --dirty
     ```
   - Pastikan seluruh test berjalan baik (`php artisan test --compact`).
4. **Lakukan Commit** dengan pesan yang jelas dan ringkas:
   ```bash
   git add .
   git commit -m "fix: hapus referensi kolom email pada AuthController register"
   ```
5. **Push & Buat Pull Request** ke branch utama repository ini beserta deskripsi perubahan yang dilakukan.

---

## 📄 8. Lisensi

File lisensi terpisah (`LICENSE`) **belum tersedia** di direktori root repository ini (walaupun `composer.json` mencantumkan `"license": "MIT"` sebagai bawaan kerangka awal Laravel).

> **Catatan untuk Pemilik Proyek:** Harap tentukan dan tambahkan file `LICENSE` resmi di direktori root proyek sebelum mendistribusikan atau mempublikasikan repository ini secara terbuka agar status hak cipta dan penggunaannya jelas.

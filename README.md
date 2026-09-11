# 📅 Agendaku - Sistem Manajemen Agenda & Acara Sekolah Multi-Role

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

> **Agendaku** adalah aplikasi web modern terpadu untuk pengelolaan agenda, kalender akademik, manajemen tugas harian (_to-do list_), serta koordinasi acara sekolah yang dirancang khusus dengan sistem kontrol akses berbasis peran (**Multi-Role**: Siswa, Guru, OSIS, dan Administrator).

---

## 📖 Daftar Isi

1. [Deskripsi Aplikasi](#-deskripsi-aplikasi)
2. [Teknologi yang Digunakan](#-teknologi-yang-digunakan-tech-stack)
3. [Fitur Utama](#-fitur-utama)
4. [Panduan Instalasi](#-panduan-instalasi-langkah-demi-langkah)
5. [Panduan Penggunaan & Akun Uji Coba](#-panduan-penggunaan)
6. [Struktur Folder Proyek](#-struktur-folder-proyek)
7. [Lisensi](#-lisensi)

---

## 📝 Deskripsi Aplikasi

Seringkali koordinasi kegiatan di lingkungan sekolah terfragmentasi: tugas pelajaran dari guru dicatat terpisah, acara kegiatan OSIS kurang tersosialisasi, dan siswa kesulitan mengatur prioritas belajarnya.

**Agendaku** hadir sebagai platform sentralisasi jadwal dan aktivitas sekolah yang interaktif dan responsif:

- **Bagi Siswa**: Menyediakan kalender dinamis, daftar tugas harian (_to-do list_) dengan indikator prioritas, serta transparansi jadwal acara sekolah.
- **Bagi Guru**: Menyediakan panel khusus untuk menerbitkan, mengubah, dan menghapus tugas akademik maupun agenda kelas secara terstruktur.
- **Bagi Pengurus OSIS**: Memfasilitasi publikasi, pembaharuan, dan pengelolaan kegiatan kesiswaan serta acara perayaan sekolah.
- **Bagi Administrator**: Memberikan visibilitas menyeluruh terhadap seluruh agenda sekolah serta pengelolaan akun civitas akademika dalam satu dasbor terpadu.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

### **Backend**

- **Bahasa Pemrograman**: PHP `^8.3`
- **Framework**: [Laravel 13.x](https://laravel.com)
- **Arsitektur**: Model-View-Controller (MVC) dengan RESTful Named Routing

### **Frontend & UI/UX**

- **Templating Engine**: Laravel Blade
- **CSS Framework**: [Tailwind CSS v4.0](https://tailwindcss.com)
- **Asset Bundler**: [Vite 8.x](https://vitejs.dev) via `laravel-vite-plugin`
- **Ikonografi**: [Iconify](https://iconify.design) (`@iconify-json/heroicons`, `@iconify/tailwind4`)
- **Interaktivitas & State Management**: Modern Vanilla JavaScript (ES6+), LocalStorage & SessionStorage Persistence

### **Database & Tools**

- **Database**: SQLite / MySQL (Database agnostic migrations)
- **Development Server**: PHP Built-in Server & Vite Dev Server
- **Code Quality**: Laravel Pint (PSR-12), Pest PHP

---

## ✨ Fitur Utama

### 🔐 1. Autentikasi & Multi-Role Access Control

- Sistem autentikasi pengguna (Login & Registrasi).
- Pembagian 4 peran dengan hak akses dan antarmuka unik:
    - **Siswa**: Fokus pada penyelesaian tugas harian dan pemantauan kalender.
    - **Guru**: Manajemen tugas sistem dan agenda pembelajaran.
    - **OSIS**: Manajemen kegiatan kesiswaan dan acara sekolah.
    - **Admin**: Supervisi sistem terpusat dan manajemen akun.

### 📅 2. Kalender Interaktif & Filter Kategori

- Visualisasi kalender bulanan yang dinamis dan interaktif.
- Navigasi tanggal dan penandaan hari ini (_Today Indicator_).
- Pemfilteran agenda berdasarkan kategori: **Pribadi**, **Sekolah**, dan **Acara (OSIS)**.

### ✅ 3. To-Do List & Pelacak Tugas Harian

- Checklist penyelesaian tugas dengan pembaruan status langsung (_real-time_).
- Tag prioritas dengan kode warna: **Tinggi (Merah)**, **Sedang (Kuning)**, **Rendah (Hijau)**.
- Filter cepat status tugas: _Semua_, _Belum Selesai_, dan _Selesai_.

### 📢 4. Banner Pengumuman Sekolah

- Pengumuman penting yang tersemat di bagian atas dasbor untuk seluruh pengguna.

### 👨‍🏫 5. Panel Guru (Kelola Tugas & Acara Sistem)

- **Tambah Agenda/Tugas Baru**: Formulir cepat untuk menetapkan judul, tanggal tenggat (_deadline_), prioritas, dan kategori.
- **Ubah Agenda (Edit via Modal)**: Modal interaktif untuk memperbarui data agenda yang sudah diterbitkan tanpa memuat ulang halaman.
- **Hapus Agenda**: Konfirmasi penghapusan data tugas/acara sistem secara aman.

### 🏛️ 6. Panel OSIS (Kelola Acara Kesiswaan)

- **Publikasi Acara**: Tambah kegiatan baru yang langsung disinkronkan ke kalender sekolah.
- **Edit Acara (Update via Modal)**: Perbarui rincian, tenggat waktu, dan skala prioritas kegiatan.
- **Hapus Acara**: Manajemen daftar acara aktif.

### ⚙️ 7. Panel Admin & Pengaturan Akun

- **Monitoring Acara Sekolah & OSIS**: Tinjau seluruh kegiatan yang terdaftar dalam sistem.
- **Tabel Pengaturan Akun Pengguna**: Menampilkan daftar akun terdaftar (ID, Nama Lengkap, Username, Peran) untuk mempermudah audit dan manajemen pengguna.

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek di komputer lokal:

### 1. Prasyarat Sistem (Prerequisites)

Pastikan perangkat Anda telah terpasang:

- **PHP** versi 8.2 atau lebih baru (disarankan PHP 8.3+)
- **Composer** versi 2.x
- **Node.js** versi 18.x atau lebih baru & **NPM**
- **Git**
- Web Server lokal (Laragon, XAMPP, atau PHP CLI)

---

### 2. Kloning Repositori

Buka terminal dan unduh repositori ini:

```bash
git clone https://github.com/ariaaaaww/Agendaku.git
cd Agendaku
```

---

### 3. Instalasi Dependensi

Jalankan instalasi dependensi backend dan frontend:

```bash
# Instal dependensi PHP (Composer)
composer install

# Instal dependensi JavaScript (NPM)
npm install
```

---

### 4. Konfigurasi Environment

Salin file konfigurasi environment dari contoh:

**Di Windows (Command Prompt / PowerShell):**

```bash
copy .env.example .env
```

**Di Linux / macOS:**

```bash
cp .env.example .env
```

Kemudian buat Application Encryption Key:

```bash
php artisan key:generate
```

---

### 5. Setup Database & Migrasi

Secara bawaan, aplikasi ini dikonfigurasi menggunakan **SQLite** (dapat juga disesuaikan ke MySQL pada file `.env`). Jalankan migrasi database:

```bash
php artisan migrate
```

---

### 6. Menjalankan Server Lokal

Jalankan server aplikasi Laravel dan asset bundler Vite:

**Opsi A — Menggunakan dua terminal terpisah:**

```bash
# Terminal 1: Jalankan server Laravel
php artisan serve

# Terminal 2: Jalankan Vite compiler
npm run dev
```

**Opsi B — Menggunakan script bersamaan:**

```bash
composer run dev
```

Aplikasi sekarang dapat diakses di browser melalui alamat:
👉 **[http://localhost:8000](http://localhost:8000)** atau **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 💡 Panduan Penggunaan

1. Buka browser dan arahkan ke `http://localhost:8000`. Anda akan dialihkan ke halaman **Login**.
2. Masukkan akun uji coba sesuai peran yang ingin Anda demonstrasikan (lihat tabel di bawah).
3. Setelah masuk:
    - Gunakan **Sidebar Navigasi** untuk beralih antara tampilan: _Kalender_, _To-Do List_, _Acara Sekolah_, _Tambah Agenda_, atau _Panel Khusus Peran_.
    - Gunakan fitur **Ubah** atau **Hapus** pada panel Guru / OSIS untuk menguji manipulasi data agenda secara interaktif.
    - Klik tombol **Logout** di pojok kanan atas atau sidebar untuk keluar.

### 🔑 Kredensial Uji Coba (Default Test Accounts)

| Peran (Role) | Nama Pengguna (_Fullname_) | Username | Password | Deskripsi Hak Akses                                                     |
| :----------- | :------------------------- | :------- | :------- | :---------------------------------------------------------------------- |
| **Siswa**    | Arianto                    | `siswa`  | `123`    | Akses kalender siswa, to-do list pribadi, pemantauan acara sekolah      |
| **Guru**     | Pak Guruh, M.Pd            | `guru`   | `123`    | Akses Panel Guru: Kelola tugas & agenda belajar (Tambah, Ubah, Hapus)   |
| **OSIS**     | Pengurus OSIS              | `osis`   | `123`    | Akses Panel OSIS: Kelola agenda & acara kesiswaan (Tambah, Ubah, Hapus) |
| **Admin**    | Administrator              | `admin`  | `123`    | Akses Panel Admin: Monitoring seluruh acara & Pengaturan akun pengguna  |

> 💡 **Tips Pengujian:** Data interaktif (tugas, pengumuman, akun) tersimpan secara persisten pada browser _LocalStorage_. Apabila ingin mereset seluruh data ke kondisi awal, cukup buka Console Browser (`F12`), ketik `localStorage.clear()`, lalu _refresh_ halaman (`F5`).

---

## 📁 Struktur Folder Proyek

Berikut adalah gambaran arsitektur dan struktur direktori penting dalam proyek ini:

```plaintext
Agendaku/
├── app/
│   └── Http/
│       └── Controllers/          # Controller pengatur alur logika & view
│           ├── AdminController.php
│           ├── AuthController.php
│           ├── StudentController.php
│           ├── StudentCouncilController.php
│           └── TeacherController.php
├── database/
│   └── migrations/               # Skema tabel database Laravel
├── resources/
│   ├── css/
│   │   └── app.css               # Styling Tailwind CSS v4
│   ├── js/
│   │   └── app.js                # Logika frontend, state management, modal, & CRUD
│   └── views/                    # Tampilan antarmuka (Blade Templates)
│       ├── admin/                # View panel Admin (Acara & Pengaturan Akun)
│       ├── auth/                 # View Login & Registrasi
│       ├── components/           # Komponen reusable (Sidebar, Dashboard Views)
│       ├── council/              # View panel OSIS (Kelola Acara)
│       ├── layouts/              # Master layout (App wrapper)
│       ├── students/             # View dasbor Siswa
│       └── teachers/             # View panel Guru (Kelola Tugas)
├── routes/
│   └── web.php                   # Definisi RESTful routing aplikasi
├── package.json                  # Dependensi frontend (Tailwind CSS, Vite, Iconify)
├── composer.json                 # Dependensi backend (Laravel 13, Pint, Pest)
└── README.md                     # Dokumentasi teknis proyek
```

---

## 📄 Lisensi

Proyek ini dibuat dan didistribusikan di bawah lisensi [MIT License](LICENSE).

# Sistem Informasi Sekolah

Sistem Informasi Sekolah berbasis web yang dibangun menggunakan Laravel dan PostgreSQL untuk membantu pengelolaan administrasi dan kegiatan akademik sekolah.

Sistem dirancang untuk mengelola data siswa, guru, orang tua/wali, kelas, mata pelajaran, jadwal, absensi, tugas, ujian, nilai, kenaikan kelas, kelulusan, dan riwayat akademik siswa dalam satu aplikasi.

## Teknologi

Project menggunakan:

- Laravel
- PostgreSQL
- Laravel Blade
- Laravel Breeze
- Spatie Laravel Permission
- Vite
- NPM
- Composer

Development lokal dapat menggunakan Laragon atau environment PHP lain yang memenuhi requirement Laravel.

## Fitur

### Authentication & Authorization

- Login
- Logout
- Role Based Access Control
- Permission Based Access Control
- Status akun

Role utama:

```text id="dk60qv"
super_admin
admin
principal
teacher
student
parent
```

### Master Data

- Tahun ajaran
- Semester
- Jurusan
- Ruangan
- Mata pelajaran
- Guru
- Siswa
- Orang tua/wali
- Kelas

### Akademik

- Pembagian siswa ke kelas
- Penugasan guru
- Wali kelas
- Jadwal pelajaran
- Absensi
- Materi pembelajaran
- Tugas
- Pengumpulan tugas
- Ujian
- Nilai

### Siklus Akademik

- Kenaikan kelas
- Siswa mengulang
- Kelulusan
- Riwayat akademik

### Laporan

Direncanakan mendukung:

- Laporan siswa
- Laporan guru
- Laporan kelas
- Laporan absensi
- Laporan nilai
- Laporan kenaikan kelas
- Laporan kelulusan
- Export PDF
- Export Excel

> Beberapa fitur masih dalam tahap pengembangan. Lihat `docs/10-current-progress.md` untuk status implementasi terbaru.

---

# Requirement

Pastikan perangkat sudah memiliki:

- PHP sesuai requirement versi Laravel yang digunakan project
- Composer
- PostgreSQL
- Node.js
- NPM
- Git

Pastikan extension PostgreSQL PHP aktif:

```ini id="ptqmc9"
extension=pdo_pgsql
extension=pgsql
```

Untuk memeriksa:

```bash id="g1v04f"
php -m
```

Pastikan terdapat:

```text id="c4k58v"
pdo_pgsql
pgsql
```

---

# Installation

## 1. Clone Repository

```bash id="u0a6qw"
git clone https://github.com/IhsanBaihaqii/Sistem-Informasi-Sekolah.git
```

Masuk ke directory project:

```bash id="2x7i92"
cd Sistem-Informasi-Sekolah
```

## 2. Install Dependency PHP

```bash id="qxxk14"
composer install
```

## 3. Install Dependency Frontend

```bash id="zhx81g"
npm install
```

## 4. Buat File Environment

Windows:

```bash id="47lxt3"
copy .env.example .env
```

Linux/macOS:

```bash id="58lktp"
cp .env.example .env
```

## 5. Generate Application Key

```bash id="32tpxz"
php artisan key:generate
```

---

# PostgreSQL

## 1. Buat Database

Buat database PostgreSQL:

```sql id="8mv16h"
CREATE DATABASE sistem_informasi_sekolah;
```

Database juga dapat dibuat melalui pgAdmin.

## 2. Konfigurasi `.env`

Buka:

```text id="zllg8n"
.env
```

Kemudian konfigurasi database:

```env id="j1fgnm"
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistem_informasi_sekolah
DB_USERNAME=postgres
DB_PASSWORD=
```

Isi `DB_PASSWORD` sesuai password PostgreSQL pada perangkat masing-masing.

Jangan commit `.env` ke repository.

---

# Database Migration

Setelah PostgreSQL terhubung:

```bash id="jdzx2x"
php artisan migrate
```

Untuk mengisi data development:

```bash id="vcmucm"
php artisan db:seed
```

Atau pada instalasi development baru:

```bash id="6ln0c3"
php artisan migrate:fresh --seed
```

> `migrate:fresh` akan menghapus seluruh tabel dan data. Gunakan hanya untuk development atau database yang aman untuk dihapus.

---

# Menjalankan Project

## Backend

Jalankan Laravel:

```bash id="f4yjg8"
php artisan serve
```

Default:

```text id="u25krd"
http://127.0.0.1:8000
```

## Frontend Development

Buka terminal kedua:

```bash id="17mb9u"
npm run dev
```

Jadi ketika development biasanya terdapat dua terminal:

```text id="8m30dv"
Terminal 1
php artisan serve

Terminal 2
npm run dev
```

Jika menggunakan Laragon dengan virtual host, konfigurasi dapat disesuaikan dengan environment lokal.

---

# Akun Development

Seeder menyediakan beberapa akun untuk keperluan development.

## Super Admin

```text id="rqdz87"
Email    : admin@super.com
Password : #Admin123
```

## Guru

```text id="90sj87"
Email    : guru@sekolah.test
Password : Guru123!
```

## Siswa

```text id="czhcbm"
Email    : siswa@sekolah.test
Password : Siswa123!
```

> Akun dan password tersebut hanya untuk development. Jangan gunakan credential tersebut pada production.

---

# Seeder

Seeder utama:

```text id="kkysxr"
database/seeders/
├── DatabaseSeeder.php
├── RolePermissionSeeder.php
├── SuperAdminSeeder.php
├── SchoolMasterSeeder.php
└── TeacherStudentSeeder.php
```

Untuk menjalankan seluruh seeder:

```bash id="yjzt25"
php artisan db:seed
```

---

# Struktur Project

Struktur utama:

```text id="7ph1cd"
Sistem-Informasi-Sekolah/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   └── Services/
│
├── bootstrap/
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── docs/
│   ├── README.md
│   ├── 01-project-overview.md
│   ├── 02-system-architecture.md
│   ├── 03-roles-permissions.md
│   ├── 04-database-design.md
│   ├── 05-business-workflow.md
│   ├── 06-feature-modules.md
│   ├── 07-routing-structure.md
│   ├── 08-development-roadmap.md
│   ├── 09-development-rules.md
│   └── 10-current-progress.md
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│
├── storage/
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

# Dokumentasi

Dokumentasi lengkap tersedia pada:

```text id="4x2wmh"
docs/
```

Mulai dari:

```text id="3x38xm"
docs/README.md
```

Dokumentasi mencakup:

| Dokumentasi         | File                             |
| ------------------- | -------------------------------- |
| Project Overview    | `docs/01-project-overview.md`    |
| System Architecture | `docs/02-system-architecture.md` |
| Roles & Permissions | `docs/03-roles-permissions.md`   |
| Database Design     | `docs/04-database-design.md`     |
| Business Workflow   | `docs/05-business-workflow.md`   |
| Feature Modules     | `docs/06-feature-modules.md`     |
| Routing Structure   | `docs/07-routing-structure.md`   |
| Development Roadmap | `docs/08-development-roadmap.md` |
| Development Rules   | `docs/09-development-rules.md`   |
| Current Progress    | `docs/10-current-progress.md`    |

Developer dan AI coding agent disarankan membaca dokumentasi sebelum melakukan perubahan besar pada project.

---

# Development Workflow

Alur pengembangan:

```text id="27vh4q"
Create Branch
     ↓
Develop Feature
     ↓
Migration / Model
     ↓
Validation
     ↓
Authorization
     ↓
Business Logic
     ↓
UI
     ↓
Testing
     ↓
Update Documentation
     ↓
Commit
```

Contoh membuat branch:

```bash id="6b9dcx"
git checkout -b feature/student-management
```

Setelah perubahan:

```bash id="7y28cw"
git status
```

```bash id="mp6xzs"
git add .
```

```bash id="1e71ip"
git commit -m "feat: add student management"
```

Push:

```bash id="h70omq"
git push origin feature/student-management
```

---

# Useful Commands

Menjalankan aplikasi:

```bash id="fexrgd"
php artisan serve
```

Menjalankan Vite:

```bash id="6dc08p"
npm run dev
```

Migration:

```bash id="sdgftg"
php artisan migrate
```

Seeder:

```bash id="qtrp6q"
php artisan db:seed
```

Melihat status migration:

```bash id="cnp3j2"
php artisan migrate:status
```

Membersihkan cache Laravel:

```bash id="9gbxyc"
php artisan optimize:clear
```

Tinker:

```bash id="h00u9g"
php artisan tinker
```

Menjalankan test:

```bash id="lf8i4b"
php artisan test
```

Build frontend:

```bash id="88k9d3"
npm run build
```

---

# Aturan Penting

Jangan commit file:

```text id="y1lt8e"
.env
```

Jangan commit:

- Password database
- API key
- Secret key
- Credential production
- File sensitif lainnya

Jangan menjalankan:

```bash id="r2fwq6"
php artisan migrate:fresh
```

pada database production.

Perintah tersebut menghapus seluruh tabel beserta data.

---

# Status Pengembangan

Project masih dalam tahap pengembangan.

Status implementasi terbaru dapat dilihat pada:

```text id="1vwsqf"
docs/10-current-progress.md
```

Roadmap dapat dilihat pada:

```text id="z2rvg2"
docs/08-development-roadmap.md
```

---

# Repository

Repository:

https://github.com/IhsanBaihaqii/Sistem-Informasi-Sekolah

---

# License

Project ini dikembangkan untuk Sistem Informasi Sekolah.

Informasi lisensi dapat ditambahkan pada file `LICENSE` apabila project akan didistribusikan secara publik.

Pastikan `.gitignore` Laravel mencakup minimal:

```gitignore id="5u7wt9"
/node_modules
/public/build
/public/hot
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
```

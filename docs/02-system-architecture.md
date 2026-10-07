### `docs/02-system-architecture.md`

# System Architecture

## Stack

```text
Backend        Laravel
Database       PostgreSQL
Frontend       Blade
Authentication Laravel Breeze
Authorization  Spatie Laravel Permission
CSS/JS         Vite / NPM
Environment    Laragon
```

## Authentication

Semua pengguna menggunakan tabel:

```text
users
```

untuk autentikasi.

Data profil disimpan terpisah.

```text
users
├── teachers
├── students
└── parents
```

Contoh:

```text
User
email: siswa@sekolah.test
role: student
        ↓
Student
NIS: 20260001
Nama: Andi Pratama
```

## Struktur Laravel

Target struktur:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Teacher/
│   │   ├── Student/
│   │   ├── Parent/
│   │   └── Principal/
│   │
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│
├── Services/
│   ├── ScheduleService.php
│   ├── AttendanceService.php
│   ├── GradeService.php
│   └── PromotionService.php
│
├── Policies/
└── Providers/
```

## Controller

Controller bertanggung jawab terhadap:

- menerima request;
- memanggil validasi;
- memanggil service/model;
- menentukan response/view.

Controller tidak boleh berisi business logic kompleks.

## Service

Business logic kompleks ditempatkan pada Service.

Contoh:

`ScheduleService`

bertanggung jawab terhadap:

- mendeteksi bentrok guru;
- mendeteksi bentrok kelas;
- mendeteksi bentrok ruangan.

`PromotionService`

bertanggung jawab terhadap proses:

- naik kelas;
- mengulang;
- membuat enrollment baru;
- menyimpan riwayat.

## Model

Model menangani:

- relationship;
- casts;
- query scope;
- atribut model yang relevan.

## Database

Semua perubahan struktur database dilakukan menggunakan Laravel Migration.

Hindari membuat atau mengubah tabel secara manual melalui pgAdmin kecuali untuk debugging.

## Security

Minimal setiap fitur harus mempertimbangkan:

- authentication;
- authorization;
- validation;
- CSRF protection;
- mass assignment;
- SQL injection;
- file upload validation;
- ownership/resource access;
- rate limiting jika diperlukan.

Frontend bukan lapisan keamanan.

Permission tetap harus diperiksa di backend.

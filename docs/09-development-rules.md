### `docs/09-development-rules.md`

# Development Rules

Dokumen ini wajib menjadi acuan ketika developer atau AI agent menulis atau mengubah kode.

## Database

Jangan mengubah database production secara manual.

Gunakan Laravel Migration.

Gunakan foreign key untuk relationship penting.

Gunakan unique constraint jika memang data harus unik.

Gunakan index pada field yang sering digunakan untuk pencarian/filter.

## Data Akademik

Jangan menghapus riwayat akademik hanya karena siswa naik kelas.

Jangan menyimpan kelas aktif siswa langsung pada `students.classroom_id`.

Gunakan `class_enrollments`.

Jangan menggunakan `repeat` sebagai status utama siswa.

## Authentication

Semua akun menggunakan `users`.

Profil pengguna dipisahkan:

```text
users
├── students
├── teachers
└── parents
```

## Authorization

Jangan hanya menyembunyikan tombol di frontend.

Backend harus memverifikasi permission atau ownership.

Contoh:

```text
students.update
attendance.create
grades.update
```

## Validation

Request create/update harus divalidasi.

Untuk modul yang mulai kompleks, gunakan Laravel Form Request.

## Business Logic

Business logic kompleks tidak boleh ditumpuk di Controller.

Gunakan Service jika operasi melibatkan beberapa model atau aturan bisnis.

Contoh:

```text
ScheduleService
PromotionService
GradeService
AttendanceService
```

## Transactions

Operasi yang mengubah beberapa tabel sekaligus harus mempertimbangkan database transaction.

Contoh kenaikan kelas:

```text
Create promotion
+
Close old enrollment
+
Create new enrollment
+
Create academic history
```

Jika satu gagal, seluruh proses harus rollback.

## Naming

Model:

```text
Student
Teacher
SchoolYear
Classroom
```

Table:

```text
students
teachers
school_years
classrooms
```

Foreign key:

```text
student_id
teacher_id
school_year_id
classroom_id
```

Permission:

```text
resource.action
```

## PostgreSQL

Kode harus kompatibel dengan PostgreSQL.

Jangan menulis query yang hanya bekerja di MySQL tanpa alasan.

## Destructive Commands

Perintah seperti:

```text
php artisan migrate:fresh
php artisan db:wipe
```

menghapus data.

Jangan digunakan setelah sistem mempunyai data penting tanpa konfirmasi dan backup.

## Seeder

Seeder development harus aman dijalankan ulang jika memungkinkan.

Gunakan:

```text
firstOrCreate
updateOrCreate
syncRoles
syncPermissions
```

sesuai kebutuhan.

## Security

Jangan commit:

```text
.env
password
API key
secret key
database credential
```

Jangan menampilkan password pengguna.

Password harus di-hash.

## AI Agent

Sebelum melakukan perubahan besar:

1. Baca dokumentasi terkait.
2. Periksa migration dan model yang sudah ada.
3. Jangan menebak field database.
4. Jangan membuat tabel duplikat.
5. Pertahankan PostgreSQL compatibility.
6. Pertahankan role/permission.
7. Pertahankan riwayat akademik.
8. Update dokumentasi jika arsitektur berubah.
9. Jangan menjalankan destructive command tanpa alasan yang jelas.
10. Jangan mengganti teknologi utama tanpa instruksi.

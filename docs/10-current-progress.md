### `docs/10-current-progress.md`

Ini penting khususnya untuk AI agent, karena menjelaskan **apa yang benar-benar sudah dibuat**, bukan hanya rencana.

# Current Development Progress

## Status

Project sedang berada pada tahap pembangunan fondasi data akademik.

## Environment

```text
Framework: Laravel
Database: PostgreSQL
Database Name: sistem_informasi_sekolah
Authentication: Laravel Breeze
Authorization: Spatie Laravel Permission
Frontend: Blade
Development: Laragon
```

## Sudah Diimplementasikan

### Authentication

- Laravel Breeze
- Login
- User authentication

### Authorization

Role:

```text
super_admin
admin
principal
teacher
student
parent
```

Permission menggunakan Spatie Laravel Permission.

Super Admin mempunyai global access melalui Laravel Gate.

### User

Tabel:

```text
users
```

Mempunyai field status akun.

Status:

```text
active
inactive
suspended
```

### Master Data

Sudah tersedia:

```text
school_years
semesters
majors
rooms
subjects
```

Relationship:

```text
SchoolYear
    ↓
Semester
```

### Teacher

Model dan tabel:

```text
teachers
```

Terhubung dengan:

```text
teachers.user_id
    ↓
users.id
```

`user_id` nullable sehingga guru dapat dibuat tanpa akun login.

### Student

Model dan tabel:

```text
students
```

Terhubung dengan:

```text
students.user_id
    ↓
users.id
```

`user_id` nullable sehingga siswa dapat dibuat tanpa akun login.

Umur tidak disimpan secara langsung.

Umur dihitung berdasarkan `birth_date`.

## Development Accounts

Terdapat akun development untuk:

```text
Super Admin
Teacher
Student
```

Credential development berada pada Seeder dan tidak boleh digunakan untuk production.

## Seeder

Seeder yang telah dibuat:

```text
RolePermissionSeeder
SuperAdminSeeder
SchoolMasterSeeder
TeacherStudentSeeder
```

`DatabaseSeeder` menjalankan seeder tersebut sesuai urutan dependensinya.

## Relasi Saat Ini

```text
                USERS
               /     \
              ↓       ↓
         TEACHERS   STUDENTS


SCHOOL_YEARS
      ↓
  SEMESTERS


MAJORS

ROOMS

SUBJECTS
```

## Belum Diimplementasikan

Tahap berikutnya:

```text
parents
student_parents
teacher_subjects
classrooms
class_enrollments
```

Setelah itu:

```text
schedules
```

Kemudian:

```text
attendance_sessions
attendances
materials
assignments
assignment_submissions
```

## Next Development Target

Urutan pengembangan berikutnya:

```text
Parent
   ↓
Student Parent
   ↓
Teacher Subject
   ↓
Classroom
   ↓
Class Enrollment
   ↓
Schedule
```

Jangan membuat Schedule sebelum relationship Teacher, Subject, Classroom, Semester, dan Room tersedia.

## Catatan

Dokumen ini harus diperbarui setiap kali milestone utama selesai agar developer dan AI agent mengetahui keadaan project sebenarnya.

Dengan struktur ini, nanti ketika kamu memberikan project ke AI agent, kamu cukup memberi instruksi seperti:

> **Baca seluruh dokumentasi di folder `docs/`, terutama `09-development-rules.md` dan `10-current-progress.md`, sebelum melakukan perubahan. Jangan mengubah arsitektur atau database tanpa menyesuaikan dokumentasi.**

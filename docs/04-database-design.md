### `docs/04-database-design.md`

# Database Design

## Database

Database utama:

```text
PostgreSQL
```

Database development:

```text
sistem_informasi_sekolah
```

## Kelompok Tabel

### Authentication

```text
users
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

### Master

```text
school_years
semesters
majors
rooms
subjects
```

### People

```text
teachers
students
parents
student_parents
```

### Academic

```text
classrooms
class_enrollments
teacher_subjects
schedules
```

### Learning

```text
attendance_sessions
attendances
materials
assignments
assignment_submissions
```

### Examination

```text
exams
questions
question_options
exam_attempts
exam_answers
```

### Grades

```text
grade_categories
grades
```

### Academic Status

```text
student_promotions
graduations
student_academic_histories
```

### System

```text
announcements
notifications
activity_logs
```

## Relationship Utama

```text
USERS
│
├── TEACHERS
│
├── STUDENTS
│
└── PARENTS
```

```text
SCHOOL_YEARS
       │
       └── SEMESTERS
```

```text
STUDENTS
    │
    ↓
CLASS_ENROLLMENTS
    │
    ↓
CLASSROOMS
```

```text
TEACHERS
    │
    ↓
TEACHER_SUBJECTS
    │
    ↓
SUBJECTS
```

Jadwal menghubungkan:

```text
Teacher
   │
   ↓
Schedule ← Subject
   ↑
   ├── Classroom
   ├── Room
   └── Semester
```

## Users

`users` hanya menyimpan informasi akun/autentikasi.

Profil akademik tidak disimpan di sini.

Hubungan:

```text
User hasOne Student
User hasOne Teacher
User hasOne Parent
```

## Students

Informasi utama:

```text
user_id nullable
nis unique
nisn nullable unique
full_name
gender
birth_place
birth_date
religion
address
phone
admission_date
photo
status
```

Umur tidak disimpan.

Umur dihitung berdasarkan `birth_date`.

## Teachers

Informasi utama:

```text
user_id nullable
nip nullable unique
nuptk nullable unique
full_name
gender
birth_place
birth_date
religion
address
phone
education
employment_status
join_date
photo
status
```

## School Years

Contoh:

```text
2025/2026
2026/2027
2027/2028
```

Hanya satu tahun ajaran yang seharusnya aktif pada satu waktu.

## Semesters

Semester berada di bawah tahun ajaran:

```text
2026/2027
├── Ganjil
└── Genap
```

## Class Enrollment

Jangan menambahkan `classroom_id` langsung pada `students`.

Gunakan:

```text
class_enrollments
```

Tujuannya agar riwayat kelas tidak hilang.

Contoh:

```text
Andi
├── 2025/2026 → X RPL 1
├── 2026/2027 → XI RPL 1
└── 2027/2028 → XII RPL 1
```

## Parent Relationship

Gunakan:

```text
parents
student_parents
```

Satu orang tua dapat memiliki beberapa siswa dan satu siswa dapat mempunyai beberapa wali.

## Jadwal

`schedules` akan menghubungkan:

```text
classroom
teacher
subject
room
semester
day_of_week
start_time
end_time
```

Sistem harus mencegah:

- guru bentrok;
- kelas bentrok;
- ruangan bentrok.

## Attendance

Gunakan dua tingkat:

```text
attendance_sessions
        ↓
attendances
```

Jangan hanya menyimpan:

```text
student_id
date
status
```

karena absensi harus diketahui berasal dari pertemuan/pelajaran mana.

## Academic History

Kenaikan kelas tidak boleh menimpa enrollment sebelumnya.

Contoh:

```text
XI RPL 1
2026/2027
    ↓
promoted
    ↓
XII RPL 1
2027/2028
```

Jika mengulang:

```text
XI RPL 1
2026/2027
    ↓
repeat
    ↓
XI RPL 2
2027/2028
```

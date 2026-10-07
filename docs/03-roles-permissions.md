### `docs/03-roles-permissions.md`

# Roles and Permissions

## Role Utama

```text
super_admin
admin
principal
teacher
student
parent
```

## Super Admin

Memiliki akses penuh terhadap sistem.

`Gate::before()` digunakan agar `super_admin` otomatis mendapatkan seluruh permission.

## Admin

Mengelola administrasi sekolah:

- siswa;
- guru;
- orang tua;
- kelas;
- jurusan;
- ruangan;
- mata pelajaran;
- tahun ajaran;
- semester;
- jadwal;
- laporan administratif.

## Principal

Kepala sekolah berorientasi monitoring.

Akses utama:

- dashboard;
- siswa;
- guru;
- kelas;
- jadwal;
- absensi;
- nilai;
- laporan.

Sebagian besar bersifat read-only.

## Teacher

Guru dapat:

- melihat kelas yang diajar;
- melihat jadwal;
- melakukan absensi;
- membuat materi;
- membuat tugas;
- memeriksa tugas;
- membuat ujian;
- memasukkan nilai.

Guru tidak boleh mengelola data global sekolah tanpa permission tambahan.

## Student

Siswa dapat:

- melihat profil sendiri;
- melihat jadwal;
- melihat absensi sendiri;
- melihat materi;
- melihat tugas;
- mengumpulkan tugas;
- mengikuti ujian;
- melihat nilai sendiri.

Siswa tidak boleh melihat data akademik pribadi siswa lain.

## Parent

Orang tua/wali dapat melihat informasi anak yang terhubung dengannya:

- profil;
- kehadiran;
- nilai;
- informasi akademik.

Orang tua tidak boleh melihat data anak yang tidak mempunyai hubungan pada `student_parents`.

## Wali Kelas

Wali kelas bukan role permanen.

Contoh:

```text
Teacher: Budi Santoso

2026/2027
└── Homeroom Teacher XI RPL 1

2027/2028
└── Tidak menjadi wali kelas
```

Authorization wali kelas harus ditentukan dari hubungan kelas/guru.

## Konvensi Permission

Gunakan pola:

```text
resource.action
```

Contoh:

```text
students.view
students.create
students.update
students.delete

teachers.view
teachers.create
teachers.update
teachers.delete

attendance.view
attendance.create
attendance.update

grades.view
grades.create
grades.update

reports.view
```

Jangan membuat permission dengan nama ambigu seperti:

```text
manage
edit_data
access
```

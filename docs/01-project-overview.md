### `docs/01-project-overview.md`

# Project Overview

## Nama Proyek

Sistem Informasi Sekolah

## Tujuan

Membangun sistem informasi sekolah berbasis web untuk mengelola kegiatan administrasi dan akademik sekolah secara terintegrasi.

Sistem menangani data siswa sejak masuk sekolah sampai lulus atau keluar serta menyimpan riwayat akademiknya.

## Pengguna Sistem

Sistem dirancang untuk:

- Super Admin
- Admin/Tata Usaha
- Kepala Sekolah
- Guru
- Siswa
- Orang Tua/Wali

Wali kelas bukan role permanen. Status wali kelas berasal dari penugasan guru terhadap kelas pada periode akademik tertentu.

## Ruang Lingkup

### Administrasi

- Manajemen user
- Role dan permission
- Data siswa
- Data guru
- Data orang tua/wali
- Tahun ajaran
- Semester
- Jurusan
- Kelas
- Ruangan
- Mata pelajaran

### Akademik

- Pembagian siswa ke kelas
- Penugasan guru
- Jadwal pelajaran
- Absensi
- Materi
- Tugas
- Pengumpulan tugas
- Ujian
- Nilai
- Rekap nilai
- Kenaikan kelas
- Siswa mengulang
- Kelulusan
- Riwayat akademik

### Informasi

- Dashboard
- Pengumuman
- Notifikasi
- Laporan
- Activity log

## Prinsip Riwayat Akademik

Data akademik tidak boleh hanya menggambarkan kondisi siswa saat ini.

Contoh:

```text
Andi
├── 2025/2026 → X RPL 1 → Naik
├── 2026/2027 → XI RPL 1 → Mengulang
├── 2027/2028 → XI RPL 2 → Naik
├── 2028/2029 → XII RPL 1 → Naik
└── 2028/2029 → Lulus
```

Data kelas sebelumnya tidak boleh hilang ketika siswa berpindah atau naik kelas.

## Status Siswa

Status utama siswa:

```text
active
graduated
transferred
withdrawn
inactive
```

`repeat` atau mengulang bukan status utama siswa.

Mengulang merupakan keputusan/riwayat akademik.

## Target Arsitektur

```text
Browser
   ↓
Laravel Routes
   ↓
Middleware
   ↓
Controller
   ↓
Form Request / Authorization
   ↓
Service
   ↓
Eloquent Model
   ↓
PostgreSQL
```

Controller tidak boleh menjadi tempat seluruh business logic.

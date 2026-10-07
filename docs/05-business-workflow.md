### `docs/05-business-workflow.md`

# Business Workflow

## Setup Awal

```text
Super Admin
    ↓
Konfigurasi Sistem
    ↓
Tahun Ajaran
    ↓
Semester
    ↓
Jurusan
    ↓
Ruangan
    ↓
Mata Pelajaran
```

## Data Sekolah

```text
Input Guru
    ↓
Input Siswa
    ↓
Input Orang Tua/Wali
```

## Akademik

```text
Buat Kelas
    ↓
Tentukan Wali Kelas
    ↓
Masukkan Siswa ke Kelas
    ↓
Hubungkan Guru + Mata Pelajaran
    ↓
Buat Jadwal
```

## Pembelajaran

```text
Jadwal
  ↓
Pertemuan
  │
  ├── Absensi
  ├── Materi
  ├── Tugas
  ├── Ujian
  └── Nilai
```

## Tugas

```text
Guru
 ↓
Buat Tugas
 ↓
Pilih Kelas/Mapel
 ↓
Tentukan Deadline
 ↓
Siswa Menerima Tugas
 ↓
Siswa Mengumpulkan
 ↓
Guru Memeriksa
 ↓
Nilai + Feedback
```

## Absensi

```text
Guru
 ↓
Jadwal Hari Ini
 ↓
Buka Pertemuan
 ↓
Daftar Siswa
 ↓
Hadir / Sakit / Izin / Alfa / Terlambat
 ↓
Simpan
 ↓
Rekap Kehadiran
```

## Akhir Semester

```text
Tugas
+
UTS
+
UAS
+
Praktik
+
Komponen lainnya
    ↓
Rekap Nilai
    ↓
Nilai Akhir
    ↓
Rapor
```

## Kenaikan Kelas

```text
Siswa Aktif
    ↓
Evaluasi Akademik
    ↓
Keputusan
   / \
  /   \
Naik  Mengulang
 |       |
 ↓       ↓
Kelas   Tingkat
Baru    Sama
```

Enrollment sebelumnya tidak dihapus.

Sistem membuat enrollment untuk periode berikutnya.

## Kelulusan

```text
Siswa Tingkat Akhir
        ↓
Evaluasi
        ↓
  ┌─────┴─────┐
  ↓           ↓
Lulus     Tidak Lulus
  ↓
Status Student
graduated
```

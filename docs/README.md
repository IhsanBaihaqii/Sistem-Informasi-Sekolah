### `docs/README.md`

# Dokumentasi Sistem Informasi Sekolah

Folder ini merupakan sumber dokumentasi utama pengembangan Sistem Informasi Sekolah.

Dokumentasi ditujukan untuk:

- Developer
- AI coding agent
- Maintainer
- Penguji
- Kontributor baru

Sebelum melakukan perubahan besar pada source code, developer atau AI agent harus membaca dokumentasi yang relevan di folder ini.

## Teknologi

- Backend: Laravel
- Database: PostgreSQL
- Frontend: Laravel Blade
- Authentication: Laravel Breeze
- Authorization: Spatie Laravel Permission
- Development environment: Laragon
- Package manager PHP: Composer
- Package manager frontend: NPM

## Struktur Dokumentasi

| File                        | Fungsi                          |
| --------------------------- | ------------------------------- |
| `01-project-overview.md`    | Tujuan dan ruang lingkup sistem |
| `02-system-architecture.md` | Arsitektur aplikasi             |
| `03-roles-permissions.md`   | Role dan hak akses              |
| `04-database-design.md`     | Struktur dan relasi database    |
| `05-business-workflow.md`   | Alur kerja sekolah              |
| `06-feature-modules.md`     | Modul dan fitur sistem          |
| `07-routing-structure.md`   | Konvensi route aplikasi         |
| `08-development-roadmap.md` | Tahapan pengembangan            |
| `09-development-rules.md`   | Aturan coding dan pengembangan  |
| `10-current-progress.md`    | Status implementasi terkini     |

## Prinsip Utama

Sistem harus:

1. Menyimpan riwayat akademik siswa.
2. Menggunakan Role Based Access Control.
3. Memisahkan akun login dari profil siswa/guru.
4. Mendukung tahun ajaran dan semester.
5. Tidak menghapus riwayat akademik ketika siswa naik kelas.
6. Mencegah konflik jadwal guru, kelas, dan ruangan.
7. Memiliki validasi dan authorization di backend.
8. Menggunakan migration sebagai sumber struktur database.
9. Menghindari perubahan database secara manual melalui PostgreSQL kecuali untuk debugging.
10. Dikembangkan secara modular agar mudah diperluas.

## Catatan untuk AI Agent

Jangan membuat asumsi struktur database tanpa membaca `04-database-design.md`.

Jangan menambahkan role atau permission baru tanpa memeriksa `03-roles-permissions.md`.

Jangan mengubah workflow akademik tanpa memeriksa `05-business-workflow.md`.

Periksa `10-current-progress.md` sebelum membuat fitur agar tidak membuat ulang fitur yang sudah selesai.

Jika implementasi mengubah arsitektur, database, workflow, role, atau fitur utama, dokumentasi terkait harus diperbarui.

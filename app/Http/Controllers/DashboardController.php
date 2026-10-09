<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $role = $user->getRoleNames()->first() ?? 'student';

        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Administrator',
            'principal' => 'Kepala Sekolah',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            'parent' => 'Orang Tua',
        ];

        $roleDescriptions = [
            'super_admin' => 'Kelola sistem, pengguna, dan pemantauan sekolah.',
            'admin' => 'Kelola data utama dan kegiatan operasional sekolah.',
            'principal' => 'Pantau perkembangan akademik dan aktivitas sekolah.',
            'teacher' => 'Akses kegiatan belajar mengajar dan administrasi kelas.',
            'student' => 'Lihat informasi pembelajaran dan kegiatan akademik.',
            'parent' => 'Pantau informasi akademik dan perkembangan anak.',
        ];

        $countTable = function (string $table): int {
            if (!Schema::hasTable($table)) {
                return 0;
            }

            return DB::table($table)->count();
        };

        $counts = [
            'students' => $countTable('students'),
            'teachers' => $countTable('teachers'),
            'classrooms' => $countTable('classrooms'),
            'subjects' => $countTable('subjects'),
            'assignments' => $countTable('assignments'),
            'exams' => $countTable('exams'),
            'attendance_sessions' => $countTable('attendance_sessions'),
            'users' => $countTable('users'),
        ];

        $dashboardCards = match ($role) {
            'super_admin' => [
                [
                    'label' => 'Total Pengguna',
                    'value' => $counts['users'],
                    'icon' => 'fa-users',
                ],
                [
                    'label' => 'Total Siswa',
                    'value' => $counts['students'],
                    'icon' => 'fa-user-graduate',
                ],
                [
                    'label' => 'Total Guru',
                    'value' => $counts['teachers'],
                    'icon' => 'fa-chalkboard-user',
                ],
                [
                    'label' => 'Total Kelas',
                    'value' => $counts['classrooms'],
                    'icon' => 'fa-school',
                ],
            ],
            'admin' => [
                [
                    'label' => 'Data Siswa',
                    'value' => $counts['students'],
                    'icon' => 'fa-user-graduate',
                ],
                [
                    'label' => 'Data Guru',
                    'value' => $counts['teachers'],
                    'icon' => 'fa-chalkboard-user',
                ],
                [
                    'label' => 'Ruang Kelas',
                    'value' => $counts['classrooms'],
                    'icon' => 'fa-school',
                ],
                [
                    'label' => 'Mata Pelajaran',
                    'value' => $counts['subjects'],
                    'icon' => 'fa-book-open',
                ],
            ],
            'principal' => [
                [
                    'label' => 'Total Siswa',
                    'value' => $counts['students'],
                    'icon' => 'fa-user-graduate',
                ],
                [
                    'label' => 'Total Guru',
                    'value' => $counts['teachers'],
                    'icon' => 'fa-chalkboard-user',
                ],
                [
                    'label' => 'Kelas',
                    'value' => $counts['classrooms'],
                    'icon' => 'fa-school',
                ],
                [
                    'label' => 'Sesi Presensi',
                    'value' => $counts['attendance_sessions'],
                    'icon' => 'fa-clipboard-check',
                ],
            ],
            'teacher' => [
                [
                    'label' => 'Total Siswa',
                    'value' => $counts['students'],
                    'icon' => 'fa-user-graduate',
                ],
                [
                    'label' => 'Mata Pelajaran',
                    'value' => $counts['subjects'],
                    'icon' => 'fa-book-open',
                ],
                [
                    'label' => 'Tugas',
                    'value' => $counts['assignments'],
                    'icon' => 'fa-file-pen',
                ],
                [
                    'label' => 'Ujian',
                    'value' => $counts['exams'],
                    'icon' => 'fa-file-circle-check',
                ],
            ],
            'student' => [
                [
                    'label' => 'Mata Pelajaran',
                    'value' => $counts['subjects'],
                    'icon' => 'fa-book-open',
                ],
                [
                    'label' => 'Tugas Tersedia',
                    'value' => $counts['assignments'],
                    'icon' => 'fa-file-pen',
                ],
                [
                    'label' => 'Ujian',
                    'value' => $counts['exams'],
                    'icon' => 'fa-file-circle-check',
                ],
                [
                    'label' => 'Kelas',
                    'value' => $counts['classrooms'],
                    'icon' => 'fa-school',
                ],
            ],
            'parent' => [
                [
                    'label' => 'Total Siswa',
                    'value' => $counts['students'],
                    'icon' => 'fa-user-graduate',
                ],
                [
                    'label' => 'Mata Pelajaran',
                    'value' => $counts['subjects'],
                    'icon' => 'fa-book-open',
                ],
                [
                    'label' => 'Tugas',
                    'value' => $counts['assignments'],
                    'icon' => 'fa-file-pen',
                ],
                [
                    'label' => 'Ujian',
                    'value' => $counts['exams'],
                    'icon' => 'fa-file-circle-check',
                ],
            ],
            default => [
                [
                    'label' => 'Mata Pelajaran',
                    'value' => $counts['subjects'],
                    'icon' => 'fa-book-open',
                ],
                [
                    'label' => 'Tugas',
                    'value' => $counts['assignments'],
                    'icon' => 'fa-file-pen',
                ],
                [
                    'label' => 'Ujian',
                    'value' => $counts['exams'],
                    'icon' => 'fa-file-circle-check',
                ],
                [
                    'label' => 'Kelas',
                    'value' => $counts['classrooms'],
                    'icon' => 'fa-school',
                ],
            ],
        };

        return view('dashboard', [
            'user' => $user,
            'role' => $role,
            'roleLabel' => $roleLabels[$role] ?? 'Pengguna',
            'roleDescription' => $roleDescriptions[$role] ?? 'Selamat datang di Sistem Informasi Sekolah.',
            'dashboardCards' => $dashboardCards,
        ]);
    }
}

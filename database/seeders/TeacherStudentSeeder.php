<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherStudentSeeder extends Seeder
{
    public function run(): void
    {
        $teacherUser = User::updateOrCreate(
            [
                'email' => 'guru@sekolah.test',
            ],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('Guru123!'),
                'status' => 'active',
            ]
        );

        $teacherUser->syncRoles(['teacher']);

        Teacher::updateOrCreate(
            [
                'nip' => '198901012020011001',
            ],
            [
                'user_id' => $teacherUser->id,
                'nuptk' => '1234567890123456',
                'full_name' => 'Budi Santoso',
                'gender' => 'Laki-laki',
                'birth_place' => 'Medan',
                'birth_date' => '1989-01-01',
                'religion' => 'Islam',
                'address' => 'Medan',
                'phone' => '081234567890',
                'education' => 'S1 Pendidikan',
                'employment_status' => 'PNS',
                'join_date' => '2020-01-01',
                'status' => 'active',
            ]
        );

        $studentUser = User::updateOrCreate(
            [
                'email' => 'siswa@sekolah.test',
            ],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('Siswa123!'),
                'status' => 'active',
            ]
        );

        $studentUser->syncRoles(['student']);

        Student::updateOrCreate(
            [
                'nis' => '20260001',
            ],
            [
                'user_id' => $studentUser->id,
                'nisn' => '0012345678',
                'full_name' => 'Andi Pratama',
                'gender' => 'Laki-laki',
                'birth_place' => 'Medan',
                'birth_date' => '2010-03-15',
                'religion' => 'Islam',
                'address' => 'Medan',
                'phone' => '081298765432',
                'admission_date' => '2026-07-01',
                'status' => 'active',
            ]
        );
    }
}

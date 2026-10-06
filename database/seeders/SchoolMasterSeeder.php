<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SchoolMasterSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::firstOrCreate(
            [
                'name' => '2026/2027',
            ],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'is_active' => true,
            ]
        );

        Semester::firstOrCreate(
            [
                'school_year_id' => $schoolYear->id,
                'name' => 'Ganjil',
            ],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-31',
                'is_active' => true,
            ]
        );

        Semester::firstOrCreate(
            [
                'school_year_id' => $schoolYear->id,
                'name' => 'Genap',
            ],
            [
                'start_date' => '2027-01-01',
                'end_date' => '2027-06-30',
                'is_active' => false,
            ]
        );

        Major::firstOrCreate(
            ['code' => 'RPL'],
            [
                'name' => 'Rekayasa Perangkat Lunak',
                'description' => 'Program keahlian Rekayasa Perangkat Lunak',
            ]
        );

        Major::firstOrCreate(
            ['code' => 'TKJ'],
            [
                'name' => 'Teknik Komputer dan Jaringan',
                'description' => 'Program keahlian Teknik Komputer dan Jaringan',
            ]
        );

        Room::firstOrCreate(
            ['code' => 'R001'],
            [
                'name' => 'Ruang Kelas 1',
                'type' => 'Kelas',
                'capacity' => 36,
                'location' => 'Lantai 1',
            ]
        );

        Room::firstOrCreate(
            ['code' => 'LAB01'],
            [
                'name' => 'Lab Komputer 1',
                'type' => 'Laboratorium',
                'capacity' => 36,
                'location' => 'Lantai 2',
            ]
        );

        Subject::firstOrCreate(
            ['code' => 'MTK'],
            [
                'name' => 'Matematika',
                'passing_grade' => 75,
            ]
        );

        Subject::firstOrCreate(
            ['code' => 'WEB'],
            [
                'name' => 'Pemrograman Web',
                'passing_grade' => 75,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            SchoolMasterSeeder::class,
            TeacherStudentSeeder::class,
            AcademicStructureSeeder::class,
            ScheduleSeeder::class,
            AttendanceSeeder::class,
            LearningSeeder::class,
            ExamSeeder::class,
            GradeSeeder::class,
        ]);
    }
}

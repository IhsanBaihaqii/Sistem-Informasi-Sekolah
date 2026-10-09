<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where('name', '2026/2027')->firstOrFail();

        $semester = Semester::where('school_year_id', $schoolYear->id)
            ->where('name', 'Ganjil')
            ->firstOrFail();

        $student = Student::where('nis', '20260001')->firstOrFail();
        $teacher = Teacher::firstOrFail();
        $classroom = Classroom::where('school_year_id', $schoolYear->id)->where('name', 'X TKJ 1')->firstOrFail();
        $subjects = Subject::orderBy('id')->take(2)->get();

        $categories = [
            ['name' => 'Tugas Mandiri', 'code' => 'TGS', 'weight' => 20],
            ['name' => 'Praktik / Portofolio', 'code' => 'PRK', 'weight' => 20],
            ['name' => 'Ujian Tengah Semester', 'code' => 'UTS', 'weight' => 30],
            ['name' => 'Ujian Akhir Semester', 'code' => 'UAS', 'weight' => 30],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = GradeCategory::updateOrCreate(
                [
                    'school_year_id' => $schoolYear->id,
                    'semester_id' => $semester->id,
                    'code' => $cat['code'],
                ],
                [
                    'name' => $cat['name'],
                    'weight' => $cat['weight'],
                    'is_active' => true,
                ]
            );
        }

        foreach ($subjects as $subject) {
            foreach ($createdCategories as $cat) {
                $score = match ($cat->code) {
                    'TGS' => 85.00,
                    'PRK' => 88.00,
                    'UTS' => 80.00,
                    'UAS' => 84.00,
                    default => 75.00,
                };

                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'classroom_id' => $classroom->id,
                        'semester_id' => $semester->id,
                        'grade_category_id' => $cat->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'score' => $score,
                        'notes' => 'Nilai contoh development',
                    ]
                );
            }
        }
    }
}

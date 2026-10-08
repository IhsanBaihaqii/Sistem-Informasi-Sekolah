<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Material;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class LearningSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where(
            'name',
            '2026/2027'
        )->firstOrFail();

        $semester = Semester::where(
            'school_year_id',
            $schoolYear->id
        )
            ->where('name', 'Ganjil')
            ->firstOrFail();

        $teacher = Teacher::firstOrFail();

        $classroom = Classroom::where(
            'school_year_id',
            $schoolYear->id
        )
            ->where('name', 'X TKJ 1')
            ->firstOrFail();

        $subjects = Subject::orderBy('id')
            ->take(2)
            ->get();

        if ($subjects->count() < 2) {
            throw new \RuntimeException(
                'Minimal membutuhkan 2 subject.'
            );
        }

        $material = Material::updateOrCreate(
            [
                'subject_id' => $subjects[0]->id,
                'teacher_id' => $teacher->id,
                'classroom_id' => $classroom->id,
                'title' => 'Pengenalan Materi',
            ],
            [
                'school_year_id' => $schoolYear->id,
                'semester_id' => $semester->id,
                'description' => 'Materi pembelajaran contoh.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        Assignment::updateOrCreate(
            [
                'subject_id' => $subjects[0]->id,
                'teacher_id' => $teacher->id,
                'classroom_id' => $classroom->id,
                'title' => 'Tugas Pertama',
            ],
            [
                'school_year_id' => $schoolYear->id,
                'semester_id' => $semester->id,
                'description' => 'Kerjakan tugas pembelajaran pertama.',
                'start_at' => now()->subHour(),
                'due_at' => now()->addDays(7),
                'max_score' => 100,
                'is_published' => true,
                'allow_late_submission' => false,
            ]
        );
    }
}

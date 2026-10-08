<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where('name', '2026/2027')
            ->firstOrFail();

        $semester = Semester::where('school_year_id', $schoolYear->id)
            ->where('name', 'Ganjil')
            ->firstOrFail();

        $classroom = Classroom::where('school_year_id', $schoolYear->id)
            ->where('name', 'X TKJ 1')
            ->firstOrFail();

        $teacher = Teacher::firstOrFail();

        $subjects = Subject::orderBy('id')
            ->take(2)
            ->get();

        if ($subjects->count() < 2) {
            throw new \RuntimeException(
                'Minimal membutuhkan 2 subject untuk ScheduleSeeder.'
            );
        }

        Schedule::updateOrCreate(
            [
                'school_year_id' => $schoolYear->id,
                'semester_id' => $semester->id,
                'classroom_id' => $classroom->id,
                'teacher_id' => $teacher->id,
                'subject_id' => $subjects[0]->id,
                'day_of_week' => 1,
                'start_time' => '08:00',
                'end_time' => '09:30',
            ],
            [
                'room_id' => $classroom->room_id,
                'notes' => 'Jadwal contoh',
                'is_active' => true,
            ]
        );

        Schedule::updateOrCreate(
            [
                'school_year_id' => $schoolYear->id,
                'semester_id' => $semester->id,
                'classroom_id' => $classroom->id,
                'teacher_id' => $teacher->id,
                'subject_id' => $subjects[1]->id,
                'day_of_week' => 1,
                'start_time' => '09:45',
                'end_time' => '11:15',
            ],
            [
                'room_id' => $classroom->room_id,
                'notes' => 'Jadwal contoh',
                'is_active' => true,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\ClassEnrollment;
use App\Models\Classroom;
use App\Models\ParentProfile;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $schoolYear = SchoolYear::where('name', '2026/2027')->firstOrFail();

            $semester1 = Semester::where('school_year_id', $schoolYear->id)
                ->where('name', 'Ganjil')
                ->firstOrFail();

            $teacher = Teacher::where('nip', '198501012010011001')->first();

            if (!$teacher) {
                $teacher = Teacher::firstOrFail();
            }

            $student = Student::where('nis', '20260001')->firstOrFail();

            $subjects = Subject::orderBy('id')->take(2)->get();

            if ($subjects->isEmpty()) {
                throw new \RuntimeException('Belum ada data subjects.');
            }

            $parent = ParentProfile::firstOrCreate(
                [
                    'nik' => '1275010101800001',
                ],
                [
                    'full_name' => 'Budi Pratama',
                    'gender' => 'male',
                    'birth_place' => 'Medan',
                    'birth_date' => '1980-01-01',
                    'religion' => 'Islam',
                    'occupation' => 'Wiraswasta',
                    'phone' => '081234567890',
                    'address' => 'Medan',
                    'status' => 'active',
                ]
            );

            $student->parents()->syncWithoutDetaching([
                $parent->id => [
                    'relationship' => 'father',
                    'is_primary' => true,
                ],
            ]);

            foreach ($subjects as $subject) {
                $teacher->subjects()->syncWithoutDetaching([
                    $subject->id => [
                        'school_year_id' => $schoolYear->id,
                    ],
                ]);
            }

            $classroom = Classroom::firstOrCreate(
                [
                    'school_year_id' => $schoolYear->id,
                    'name' => 'X TKJ 1',
                ],
                [
                    'major_id' => null,
                    'grade_level' => 10,
                    'homeroom_teacher_id' => $teacher->id,
                    'room_id' => null,
                    'capacity' => 36,
                    'is_active' => true,
                ]
            );

            $classroom->update([
                'homeroom_teacher_id' => $teacher->id,
            ]);

            ClassEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $classroom->id,
                    'semester_id' => $semester1->id,
                ],
                [
                    'enrolled_at' => '2026-07-01',
                    'ended_at' => null,
                    'status' => 'active',
                    'notes' => null,
                ]
            );
        });
    }
}

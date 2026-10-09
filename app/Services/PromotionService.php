<?php

namespace App\Services;

use App\Models\ClassEnrollment;
use App\Models\Classroom;
use App\Models\Graduation;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAcademicHistory;
use App\Models\StudentPromotion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PromotionService
{
    public function promoteStudent(
        Student $student,
        Classroom $fromClassroom,
        Classroom $toClassroom,
        Semester $currentSemester,
        Semester $nextSemester,
        ?User $approvedBy = null,
        ?float $finalScore = null,
        ?string $notes = null
    ): StudentPromotion {
        if ((int) $toClassroom->school_year_id !== (int) $nextSemester->school_year_id) {
            throw ValidationException::withMessages([
                'to_classroom_id' => 'Tahun ajaran kelas tujuan tidak sesuai dengan semester baru.',
            ]);
        }

        return DB::transaction(function () use (
            $student,
            $fromClassroom,
            $toClassroom,
            $currentSemester,
            $nextSemester,
            $approvedBy,
            $finalScore,
            $notes
        ) {
            $promotion = StudentPromotion::create([
                'student_id' => $student->id,
                'from_classroom_id' => $fromClassroom->id,
                'to_classroom_id' => $toClassroom->id,
                'from_school_year_id' => $fromClassroom->school_year_id,
                'to_school_year_id' => $toClassroom->school_year_id,
                'status' => 'promoted',
                'decision_date' => now()->toDateString(),
                'notes' => $notes,
                'approved_by' => $approvedBy?->id,
            ]);

            ClassEnrollment::where('student_id', $student->id)
                ->where('classroom_id', $fromClassroom->id)
                ->where('semester_id', $currentSemester->id)
                ->update([
                    'status' => 'completed',
                    'ended_at' => now()->toDateString(),
                ]);

            ClassEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $toClassroom->id,
                    'semester_id' => $nextSemester->id,
                ],
                [
                    'enrolled_at' => now()->toDateString(),
                    'ended_at' => null,
                    'status' => 'active',
                ]
            );

            StudentAcademicHistory::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $fromClassroom->school_year_id,
                    'semester_id' => $currentSemester->id,
                    'classroom_id' => $fromClassroom->id,
                ],
                [
                    'final_score' => $finalScore,
                    'academic_status' => 'promoted',
                    'notes' => $notes,
                ]
            );

            return $promotion;
        });
    }

    public function retainStudent(
        Student $student,
        Classroom $currentClassroom,
        Classroom $targetClassroom,
        Semester $currentSemester,
        Semester $nextSemester,
        ?User $approvedBy = null,
        ?float $finalScore = null,
        ?string $notes = null
    ): StudentPromotion {
        return DB::transaction(function () use (
            $student,
            $currentClassroom,
            $targetClassroom,
            $currentSemester,
            $nextSemester,
            $approvedBy,
            $finalScore,
            $notes
        ) {
            $promotion = StudentPromotion::create([
                'student_id' => $student->id,
                'from_classroom_id' => $currentClassroom->id,
                'to_classroom_id' => $targetClassroom->id,
                'from_school_year_id' => $currentClassroom->school_year_id,
                'to_school_year_id' => $targetClassroom->school_year_id,
                'status' => 'retained',
                'decision_date' => now()->toDateString(),
                'notes' => $notes,
                'approved_by' => $approvedBy?->id,
            ]);

            ClassEnrollment::where('student_id', $student->id)
                ->where('classroom_id', $currentClassroom->id)
                ->where('semester_id', $currentSemester->id)
                ->update([
                    'status' => 'repeated',
                    'ended_at' => now()->toDateString(),
                ]);

            ClassEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $targetClassroom->id,
                    'semester_id' => $nextSemester->id,
                ],
                [
                    'enrolled_at' => now()->toDateString(),
                    'ended_at' => null,
                    'status' => 'active',
                ]
            );

            StudentAcademicHistory::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $currentClassroom->school_year_id,
                    'semester_id' => $currentSemester->id,
                    'classroom_id' => $currentClassroom->id,
                ],
                [
                    'final_score' => $finalScore,
                    'academic_status' => 'retained',
                    'notes' => $notes,
                ]
            );

            return $promotion;
        });
    }

    public function graduateStudent(
        Student $student,
        Classroom $classroom,
        Semester $semester,
        ?string $certificateNumber = null,
        ?User $approvedBy = null,
        ?float $finalScore = null,
        ?string $notes = null
    ): Graduation {
        return DB::transaction(function () use (
            $student,
            $classroom,
            $semester,
            $certificateNumber,
            $approvedBy,
            $finalScore,
            $notes
        ) {
            $graduation = Graduation::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $classroom->school_year_id,
                ],
                [
                    'graduation_date' => now()->toDateString(),
                    'certificate_number' => $certificateNumber,
                    'status' => 'graduated',
                    'notes' => $notes,
                    'approved_by' => $approvedBy?->id,
                ]
            );

            ClassEnrollment::where('student_id', $student->id)
                ->where('classroom_id', $classroom->id)
                ->where('semester_id', $semester->id)
                ->update([
                    'status' => 'graduated',
                    'ended_at' => now()->toDateString(),
                ]);

            StudentAcademicHistory::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $classroom->school_year_id,
                    'semester_id' => $semester->id,
                    'classroom_id' => $classroom->id,
                ],
                [
                    'final_score' => $finalScore,
                    'academic_status' => 'graduated',
                    'notes' => $notes,
                ]
            );

            $student->update(['status' => 'graduated']);

            return $graduation;
        });
    }
}

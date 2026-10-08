<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentService
{
    public function createSubmission(
        Assignment $assignment,
        Student $student,
        array $data
    ): AssignmentSubmission {
        if (!$assignment->is_published) {
            throw ValidationException::withMessages([
                'assignment' => 'Tugas belum dipublikasikan.',
            ]);
        }

        if (
            $assignment->start_at &&
            now()->lt($assignment->start_at)
        ) {
            throw ValidationException::withMessages([
                'assignment' => 'Tugas belum dapat dikerjakan.',
            ]);
        }

        $isLate = $assignment->due_at &&
            now()->gt($assignment->due_at);

        if ($isLate && !$assignment->allow_late_submission) {
            throw ValidationException::withMessages([
                'assignment' => 'Batas pengumpulan tugas sudah berakhir.',
            ]);
        }

        return DB::transaction(function () use (
            $assignment,
            $student,
            $data,
            $isLate
        ) {
            return AssignmentSubmission::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                ],
                [
                    'submitted_at' => now(),
                    'answer' => $data['answer'] ?? null,
                    'file_path' => $data['file_path'] ?? null,
                    'file_name' => $data['file_name'] ?? null,
                    'status' => $isLate ? 'late' : 'submitted',
                ]
            );
        });
    }

    public function gradeSubmission(
        AssignmentSubmission $submission,
        float $score,
        ?string $feedback = null
    ): AssignmentSubmission {
        $maxScore = (float) $submission
            ->assignment
            ->max_score;

        if ($score < 0 || $score > $maxScore) {
            throw ValidationException::withMessages([
                'score' => "Nilai harus berada di antara 0 dan {$maxScore}.",
            ]);
        }

        $submission->update([
            'score' => $score,
            'teacher_feedback' => $feedback,
            'status' => 'graded',
        ]);

        return $submission->fresh();
    }
}

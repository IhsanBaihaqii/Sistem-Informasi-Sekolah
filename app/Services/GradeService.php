<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class GradeService
{
    public function saveGrade(
        Student $student,
        Subject $subject,
        Classroom $classroom,
        Semester $semester,
        GradeCategory $category,
        float $score,
        ?Teacher $teacher = null,
        ?string $notes = null
    ): Grade {
        if ($score < 0 || $score > 100) {
            throw ValidationException::withMessages([
                'score' => 'Nilai harus berada di rentang 0 sampai 100.',
            ]);
        }

        return Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'classroom_id' => $classroom->id,
                'semester_id' => $semester->id,
                'grade_category_id' => $category->id,
            ],
            [
                'teacher_id' => $teacher?->id,
                'score' => $score,
                'notes' => $notes,
            ]
        );
    }

    public function calculateSubjectFinalScore(
        Student $student,
        Subject $subject,
        Classroom $classroom,
        Semester $semester
    ): array {
        $grades = Grade::with('category')
            ->where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('classroom_id', $classroom->id)
            ->where('semester_id', $semester->id)
            ->get();

        if ($grades->isEmpty()) {
            return [
                'final_score' => 0.0,
                'predicate' => 'E',
                'is_passed' => false,
                'grades' => $grades,
            ];
        }

        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($grades as $grade) {
            $weight = (float) ($grade->category->weight ?? 1);
            $totalWeight += $weight;
            $weightedSum += ((float) $grade->score) * $weight;
        }

        $finalScore = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0.0;

        $predicate = match (true) {
            $finalScore >= 85 => 'A',
            $finalScore >= 75 => 'B',
            $finalScore >= 65 => 'C',
            $finalScore >= 50 => 'D',
            default => 'E',
        };

        $passingGrade = (float) ($subject->passing_grade ?? 75);
        $isPassed = $finalScore >= $passingGrade;

        return [
            'final_score' => $finalScore,
            'predicate' => $predicate,
            'is_passed' => $isPassed,
            'grades' => $grades,
        ];
    }

    public function calculateSemesterSummary(
        Student $student,
        Classroom $classroom,
        Semester $semester
    ): array {
        $subjects = Subject::where('is_active', true)->get();
        $results = [];
        $totalScores = 0;
        $evaluatedCount = 0;
        $passedCount = 0;

        foreach ($subjects as $subject) {
            $subResult = $this->calculateSubjectFinalScore($student, $subject, $classroom, $semester);
            if ($subResult['grades']->isNotEmpty()) {
                $totalScores += $subResult['final_score'];
                $evaluatedCount++;
                if ($subResult['is_passed']) {
                    $passedCount++;
                }
            }

            $results[$subject->id] = [
                'subject' => $subject,
                'result' => $subResult,
            ];
        }

        $averageScore = $evaluatedCount > 0 ? round($totalScores / $evaluatedCount, 2) : 0.0;

        return [
            'student' => $student,
            'semester' => $semester,
            'classroom' => $classroom,
            'average_score' => $averageScore,
            'evaluated_subjects_count' => $evaluatedCount,
            'passed_subjects_count' => $passedCount,
            'subject_results' => $results,
        ];
    }
}

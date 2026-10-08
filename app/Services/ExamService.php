<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\QuestionOption;
use App\Models\Student;
use Illuminate\Validation\ValidationException;

class ExamService
{
    public function startExam(Exam $exam, Student $student): ExamAttempt
    {
        if (!$exam->is_published) {
            throw ValidationException::withMessages([
                'exam' => 'Ujian belum dipublikasikan.',
            ]);
        }

        if ($exam->start_at && now()->lt($exam->start_at)) {
            throw ValidationException::withMessages([
                'exam' => 'Ujian belum dimulai.',
            ]);
        }

        if ($exam->end_at && now()->gt($exam->end_at)) {
            throw ValidationException::withMessages([
                'exam' => 'Waktu ujian sudah berakhir.',
            ]);
        }

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();

        if ($attempt) {
            return $attempt;
        }

        return ExamAttempt::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);
    }

    public function saveAnswer(
        ExamAttempt $attempt,
        int $questionId,
        ?int $optionId = null,
        ?string $answerText = null
    ): ExamAnswer {
        if ($attempt->status !== 'in_progress') {
            throw ValidationException::withMessages([
                'attempt' => 'Ujian sudah dikumpulkan.',
            ]);
        }

        $question = $attempt->exam
            ->questions()
            ->findOrFail($questionId);

        $points = 0;

        if ($optionId) {
            $option = QuestionOption::where('id', $optionId)
                ->where('question_id', $question->id)
                ->first();

            if (!$option) {
                throw ValidationException::withMessages([
                    'option' => 'Pilihan jawaban tidak valid.',
                ]);
            }

            if ($option->is_correct) {
                $points = $question->points;
            }
        }

        return ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'question_option_id' => $optionId,
                'answer_text' => $answerText,
                'points_earned' => $points,
            ]
        );
    }

    public function submitExam(ExamAttempt $attempt): ExamAttempt
    {
        if ($attempt->status !== 'in_progress') {
            throw ValidationException::withMessages([
                'attempt' => 'Ujian sudah dikumpulkan.',
            ]);
        }

        $attempt->load('exam.questions');

        $totalPoints = $attempt->exam->questions->sum('points');

        $earnedPoints = $attempt->answers()->sum('points_earned');

        $score = $totalPoints > 0
            ? ($earnedPoints / $totalPoints) * 100
            : 0;

        $attempt->update([
            'submitted_at' => now(),
            'score' => round($score, 2),
            'status' => 'graded',
        ]);

        return $attempt->fresh();
    }
}

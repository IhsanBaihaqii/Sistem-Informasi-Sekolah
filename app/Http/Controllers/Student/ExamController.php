<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    private function student(Request $request)
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        return $student;
    }

    private function isEnrolled(Exam $exam, int $studentId): bool
    {
        return DB::table('class_enrollments')
            ->where('student_id', $studentId)
            ->where('classroom_id', $exam->classroom_id)
            ->where('semester_id', $exam->semester_id)
            ->where('status', 'active')
            ->exists();
    }

    public function index(Request $request)
    {
        $student = $this->student($request);

        $classroomIds = DB::table('class_enrollments')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('classroom_id');

        $exams = Exam::whereIn('classroom_id', $classroomIds)
            ->where('is_published', true)
            ->with(['subject', 'classroom'])
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('student.exams.index', compact('exams'));
    }

    public function show(Request $request, Exam $exam)
    {
        $student = $this->student($request);

        abort_unless($exam->is_published, 404);
        abort_unless($this->isEnrolled($exam, $student->id), 403);

        $attempt = $exam->attempts()
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        return view('student.exams.show', compact('exam', 'attempt'));
    }

    public function start(
        Request $request,
        Exam $exam,
        ExamService $examService
    ) {
        $student = $this->student($request);

        abort_unless($this->isEnrolled($exam, $student->id), 403);

        $attempt = $examService->startExam($exam, $student);

        return redirect()->route('student.exams.take', $attempt);
    }


    public function take(
        Request $request,
        ExamAttempt $attempt,
        ExamService $examService
    ) {
        $student = $this->student($request);

        abort_unless($attempt->student_id === $student->id, 403);

        if ($attempt->status !== 'in_progress') {
            return redirect()
                ->route('student.exams.index')
                ->with('success', 'Ujian ini sudah dikumpulkan.');
        }

        $attempt->load([
            'exam.questions' => fn($query) => $query->orderBy('question_order'),
            'answers',
        ]);

        $exam = $attempt->exam;

        $deadline = $attempt->started_at && $exam->duration_minutes
            ? $attempt->started_at->copy()->addMinutes($exam->duration_minutes)
            : null;

        $timeExpired = (
            $deadline && now()->greaterThanOrEqualTo($deadline)
        ) || (
            $exam->end_at && now()->greaterThanOrEqualTo($exam->end_at)
        );

        if ($timeExpired) {
            $examService->submitExam($attempt);

            return redirect()
                ->route('student.exams.index')
                ->with(
                    'success',
                    'Waktu ujian berakhir. Jawaban yang tersimpan telah dikumpulkan.'
                );
        }

        return view('student.exams.take', compact('attempt'));
    }

    public function answer(
        Request $request,
        ExamAttempt $attempt,
        ExamService $examService
    ) {
        $student = $this->student($request);

        abort_unless($attempt->student_id === $student->id, 403);

        if ($attempt->status !== 'in_progress') {
            abort(403, 'Ujian sudah dikumpulkan.');
        }

        if (
            $attempt->exam->duration_minutes &&
            now()->greaterThan(
                $attempt->started_at->copy()->addMinutes(
                    $attempt->exam->duration_minutes
                )
            )
        ) {
            throw ValidationException::withMessages([
                'exam' => 'Waktu ujian telah habis.',
            ]);
        }

        $data = $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'question_option_id' => ['nullable', 'integer', 'exists:question_options,id'],
        ]);

        $question = Question::where('exam_id', $attempt->exam_id)
            ->findOrFail($data['question_id']);

        $examService->saveAnswer(
            $attempt,
            $question->id,
            $data['question_option_id'] ?? null
        );

        return back()->with('success', 'Jawaban tersimpan.');
    }

    public function submit(
        Request $request,
        ExamAttempt $attempt,
        ExamService $examService
    ) {
        $student = $this->student($request);

        abort_unless($attempt->student_id === $student->id, 403);

        if ($attempt->status === 'in_progress') {
            $examService->submitExam($attempt);
        }

        return redirect()
            ->route('student.exams.index')
            ->with('success', 'Ujian berhasil dikumpulkan.');
    }
}

<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $exams = Exam::where('teacher_id', $teacher->id)
            ->with(['subject', 'classroom'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(10);

        return view('teacher.exams.index', compact('exams'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->teacher, 403);

        $schoolYears = SchoolYear::orderByDesc('id')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();

        $classrooms = Classroom::where('is_active', true)
            ->with('schoolYear')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.create', compact(
            'schoolYears',
            'subjects',
            'classrooms'
        ));
    }

    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher, 403);

        $data = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'passing_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $classroom = Classroom::findOrFail($data['classroom_id']);

        if ((int) $classroom->school_year_id !== (int) $data['school_year_id']) {
            throw ValidationException::withMessages([
                'classroom_id' => 'Kelas tidak sesuai dengan tahun ajaran.',
            ]);
        }

        $semester = Semester::findOrFail($data['semester_id']);

        if ((int) $semester->school_year_id !== (int) $data['school_year_id']) {
            throw ValidationException::withMessages([
                'semester_id' => 'Semester tidak sesuai dengan tahun ajaran.',
            ]);
        }

        $exam = Exam::create([
            ...$data,
            'teacher_id' => $teacher->id,
            'is_published' => false,
        ]);

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Ujian berhasil dibuat. Tambahkan soal sebelum dipublikasikan.');
    }

    public function show(Request $request, Exam $exam)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher && $exam->teacher_id === $teacher->id, 403);

        $exam->load([
            'schoolYear',
            'semester',
            'subject',
            'classroom',
            'questions.options',
            'attempts.student',
        ]);

        return view('teacher.exams.show', compact('exam'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher && $exam->teacher_id === $teacher->id, 403);

        if ($exam->is_published) {
            return back()->withErrors([
                'question' => 'Ujian yang sudah dipublikasikan tidak dapat diubah.',
            ]);
        }

        $data = $request->validate([
            'question_text' => ['required', 'string'],
            'points' => ['required', 'numeric', 'min:0.01', 'max:100'],
            'options' => ['required', 'array', 'size:4'],
            'options.*.text' => ['required', 'string', 'max:2000'],
            'correct_option' => ['required', 'integer', 'between:0,3'],
        ]);

        DB::transaction(function () use ($exam, $data) {
            $question = $exam->questions()->create([
                'question_text' => $data['question_text'],
                'question_type' => 'multiple_choice',
                'points' => $data['points'],
                'question_order' => $exam->questions()->count() + 1,
            ]);

            foreach ($data['options'] as $index => $option) {
                $question->options()->create([
                    'option_label' => chr(65 + $index),
                    'option_text' => $option['text'],
                    'is_correct' => $index === (int) $data['correct_option'],
                    'option_order' => $index + 1,
                ]);
            }
        });

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function publish(Request $request, Exam $exam)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher && $exam->teacher_id === $teacher->id, 403);

        if ($exam->questions()->count() === 0) {
            return back()->withErrors([
                'exam' => 'Tambahkan minimal satu soal sebelum mempublikasikan ujian.',
            ]);
        }

        $exam->update(['is_published' => true]);

        return back()->with('success', 'Ujian berhasil dipublikasikan.');
    }

    public function results(Request $request, Exam $exam)
    {
        $teacher = $request->user()->teacher;

        abort_unless($teacher && $exam->teacher_id === $teacher->id, 403);

        $attempts = $exam->attempts()
            ->with('student')
            ->whereIn('status', ['submitted', 'graded'])
            ->latest('submitted_at')
            ->paginate(20);

        return view('teacher.exams.results', compact('exam', 'attempts'));
    }
}

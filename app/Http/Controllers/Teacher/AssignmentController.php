<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Services\AssignmentService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->withCount('submissions')
            ->latest()
            ->paginate(15);

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $schoolYears = SchoolYear::orderByDesc('id')->get();
        $classrooms = Classroom::where('is_active', true)->with('schoolYear')->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();

        return view('teacher.assignments.create', compact('schoolYears', 'classrooms', 'subjects'));
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
            'due_at' => ['required', 'date', 'after:start_at'],
            'max_score' => ['required', 'numeric', 'min:1', 'max:100'],
            'allow_late_submission' => ['boolean'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $fileName = $uploaded->getClientOriginalName();
            $filePath = $uploaded->store('assignments', 'public');
        }

        Assignment::create([
            'school_year_id' => $data['school_year_id'],
            'semester_id' => $data['semester_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $teacher->id,
            'classroom_id' => $data['classroom_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_at' => $data['start_at'] ?? now(),
            'due_at' => $data['due_at'],
            'max_score' => $data['max_score'],
            'allow_late_submission' => $request->boolean('allow_late_submission', false),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'is_published' => true,
        ]);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Tugas berhasil dibuat dan dipublikasikan.');
    }

    public function show(Request $request, Assignment $assignment)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher && $assignment->teacher_id === $teacher->id, 403);

        $assignment->load(['classroom', 'subject', 'submissions.student']);

        return view('teacher.assignments.show', compact('assignment'));
    }

    public function gradeSubmission(Request $request, AssignmentSubmission $submission, AssignmentService $assignmentService)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher && $submission->assignment->teacher_id === $teacher->id, 403);

        $data = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:' . $submission->assignment->max_score],
            'teacher_feedback' => ['nullable', 'string'],
        ]);

        $assignmentService->gradeSubmission(
            $submission,
            (float) $data['score'],
            $data['teacher_feedback'] ?? null
        );

        return back()->with('success', 'Nilai pengumpulan tugas berhasil disimpan.');
    }
}

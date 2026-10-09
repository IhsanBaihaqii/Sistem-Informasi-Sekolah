<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $classroomIds = DB::table('class_enrollments')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('classroom_id');

        $assignments = Assignment::whereIn('classroom_id', $classroomIds)
            ->where('is_published', true)
            ->with(['subject', 'teacher', 'classroom', 'submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->latest('due_at')
            ->paginate(15);

        return view('student.assignments.index', compact('assignments'));
    }

    public function show(Request $request, Assignment $assignment)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        $assignment->load(['subject', 'teacher', 'classroom']);

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment, AssignmentService $assignmentService)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $data = $request->validate([
            'answer' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $fileName = $uploaded->getClientOriginalName();
            $filePath = $uploaded->store('submissions', 'public');
        }

        $assignmentService->createSubmission($assignment, $student, [
            'answer' => $data['answer'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }
}

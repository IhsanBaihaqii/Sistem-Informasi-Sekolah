<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Services\GradeService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $classrooms = Classroom::where('is_active', true)->with('schoolYear')->orderBy('name')->get();
        $subjects = $teacher->subjects()->where('is_active', true)->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::where('is_active', true)->get();
        }

        $selectedClassroomId = $request->input('classroom_id');
        $selectedSubjectId = $request->input('subject_id');

        $classroom = $selectedClassroomId ? Classroom::find($selectedClassroomId) : null;
        $subject = $selectedSubjectId ? Subject::find($selectedSubjectId) : null;

        $students = collect();
        $categories = collect();
        $grades = collect();

        if ($classroom && $subject) {
            $students = $classroom->enrollments()
                ->where('status', 'active')
                ->with('student')
                ->get()
                ->pluck('student');

            $categories = GradeCategory::where('school_year_id', $classroom->school_year_id)
                ->where('is_active', true)
                ->get();

            $grades = Grade::where('classroom_id', $classroom->id)
                ->where('subject_id', $subject->id)
                ->get()
                ->groupBy('student_id');
        }

        return view('teacher.grades.index', compact(
            'classrooms',
            'subjects',
            'classroom',
            'subject',
            'students',
            'categories',
            'grades'
        ));
    }

    public function store(Request $request, GradeService $gradeService)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $data = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'student_id' => ['required', 'exists:students,id'],
            'grade_category_id' => ['required', 'exists:grade_categories,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $classroom = Classroom::findOrFail($data['classroom_id']);
        $subject = Subject::findOrFail($data['subject_id']);
        $student = Student::findOrFail($data['student_id']);
        $category = GradeCategory::findOrFail($data['grade_category_id']);
        $semester = Semester::where('school_year_id', $classroom->school_year_id)->firstOrFail();

        $gradeService->saveGrade(
            $student,
            $subject,
            $classroom,
            $semester,
            $category,
            (float) $data['score'],
            $teacher,
            $data['notes'] ?? null
        );

        return back()->with('success', 'Nilai siswa berhasil disimpan.');
    }
}

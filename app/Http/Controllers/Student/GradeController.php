<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\Semester;
use App\Services\GradeService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request, GradeService $gradeService)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $enrollments = ClassEnrollment::where('student_id', $student->id)
            ->with(['classroom.schoolYear', 'semester'])
            ->latest('id')
            ->get();

        $selectedEnrollmentId = $request->input('enrollment_id', $enrollments->first()?->id);
        $activeEnrollment = $enrollments->firstWhere('id', $selectedEnrollmentId);

        $summary = null;
        if ($activeEnrollment) {
            $summary = $gradeService->calculateSemesterSummary(
                $student,
                $activeEnrollment->classroom,
                $activeEnrollment->semester
            );
        }

        return view('student.grades.index', compact('enrollments', 'activeEnrollment', 'summary'));
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $classroomIds = DB::table('class_enrollments')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('classroom_id');

        $materials = Material::whereIn('classroom_id', $classroomIds)
            ->where('is_published', true)
            ->with(['subject', 'teacher', 'classroom'])
            ->latest('published_at')
            ->paginate(15);

        return view('student.materials.index', compact('materials'));
    }
}

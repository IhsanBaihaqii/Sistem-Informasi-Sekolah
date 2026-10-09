<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $materials = Material::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->latest()
            ->paginate(15);

        return view('teacher.materials.index', compact('materials'));
    }

    public function create(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $schoolYears = SchoolYear::orderByDesc('id')->get();
        $classrooms = Classroom::where('is_active', true)->with('schoolYear')->orderBy('name')->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();

        return view('teacher.materials.create', compact('schoolYears', 'classrooms', 'subjects'));
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
            'file' => ['nullable', 'file', 'max:20480'], // max 20MB
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $fileName = $uploaded->getClientOriginalName();
            $fileType = $uploaded->getClientOriginalExtension();
            $filePath = $uploaded->store('materials', 'public');
        }

        Material::create([
            'school_year_id' => $data['school_year_id'],
            'semester_id' => $data['semester_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $teacher->id,
            'classroom_id' => $data['classroom_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'is_published' => true,
            'published_at' => now(),
        ]);

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Materi pembelajaran berhasil dibagikan.');
    }

    public function destroy(Request $request, Material $material)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher && $material->teacher_id === $teacher->id, 403);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Materi pembelajaran berhasil dihapus.');
    }
}

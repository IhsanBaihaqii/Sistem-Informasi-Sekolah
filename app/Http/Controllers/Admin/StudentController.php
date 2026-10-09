<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('students');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'ilike', "%{$search}%")
                    ->orWhere('nis', 'ilike', "%{$search}%")
                    ->orWhere('nisn', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query
            ->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        $totalStudents = DB::table('students')->count();
        $activeStudents = DB::table('students')->where('status', 'active')->count();
        $inactiveStudents = DB::table('students')->where('status', '!=', 'active')->count();

        return view('admin.students.index', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'inactiveStudents'
        ));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('students', 'public');
        }

        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('students')->insert($validated);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        abort_if(!$student, 404);

        return view('admin.students.show', compact('student'));
    }

    public function edit(string $id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        abort_if(!$student, 404);

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, string $id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        abort_if(!$student, 404);

        $validated = $this->validateStudent($request, (int) $id);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('students', 'public');

            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
        }

        $validated['updated_at'] = now();

        DB::table('students')->where('id', $id)->update($validated);

        return redirect()
            ->route('admin.students.show', $id)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        abort_if(!$student, 404);

        // Relasi student_parents mengikuti cascadeOnDelete di database.
        DB::table('students')->where('id', $id)->delete();

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    private function validateStudent(Request $request, ?int $studentId = null): array
    {
        return $request->validate([
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id'),
                Rule::unique('students', 'user_id')->ignore($studentId),
            ],
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('students', 'nis')->ignore($studentId),
            ],
            'nisn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('students', 'nisn')->ignore($studentId),
            ],
            'full_name' => ['required', 'string', 'max:150'],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'religion' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'admission_date' => ['nullable', 'date', 'before_or_equal:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated', 'transferred'])],
        ]);
    }
}

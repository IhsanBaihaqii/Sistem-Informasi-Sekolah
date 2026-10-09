
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Buat Ujian</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('teacher.exams.store') }}"
                  class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">Judul ujian</label>
                    <input name="title" value="{{ old('title') }}" required maxlength="200"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Tahun ajaran</label>
                    <select name="school_year_id" required
                            class="w-full rounded-lg border-gray-300">
                        <option value="">Pilih tahun ajaran</option>
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year->id }}" @selected(old('school_year_id') == $year->id)>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Semester</label>
                    <select name="semester_id" required class="w-full rounded-lg border-gray-300">
                        <option value="">Pilih semester</option>
                        @foreach (\App\Models\Semester::with('schoolYear')->orderByDesc('id')->get() as $semester)
                            <option value="{{ $semester->id }}" @selected(old('semester_id') == $semester->id)>
                                {{ $semester->schoolYear->name }} — {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Mata pelajaran</label>
                    <select name="subject_id" required class="w-full rounded-lg border-gray-300">
                        <option value="">Pilih mata pelajaran</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Kelas</label>
                    <select name="classroom_id" required class="w-full rounded-lg border-gray-300">
                        <option value="">Pilih kelas</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id') == $classroom->id)>
                                {{ $classroom->name }} — {{ $classroom->schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Mulai ujian</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Akhir ujian</label>
                        <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Durasi (menit)</label>
                        <input type="number" name="duration_minutes" min="1" max="600"
                               value="{{ old('duration_minutes', 60) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Nilai minimum lulus</label>
                        <input type="number" name="passing_score" min="0" max="100" step="0.01"
                               value="{{ old('passing_score', 75) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                        Simpan Draft
                    </button>
                    <a href="{{ route('teacher.exams.index') }}"
                       class="rounded-lg border border-gray-300 px-5 py-2.5 text-gray-700">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
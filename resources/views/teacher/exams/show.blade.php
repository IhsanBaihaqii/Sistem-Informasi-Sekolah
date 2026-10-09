
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ $exam->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg bg-red-50 p-4 text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <p class="text-sm text-gray-600">
                    {{ $exam->subject->name }} · {{ $exam->classroom->name }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Status: {{ $exam->is_published ? 'Dipublikasikan' : 'Draft' }}
                </p>

                @if (!$exam->is_published)
                    <form method="POST" action="{{ route('teacher.exams.publish', $exam) }}" class="mt-4">
                        @csrf
                        <button class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700">
                            Publikasikan Ujian
                        </button>
                    </form>
                @endif
            </div>

            @if (!$exam->is_published)
                <form method="POST" action="{{ route('teacher.exams.questions.store', $exam) }}"
                      class="space-y-5 rounded-xl border border-gray-200 bg-white p-6">
                    @csrf

                    <h3 class="text-lg font-bold">Tambah soal pilihan ganda</h3>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Pertanyaan</label>
                        <textarea name="question_text" required rows="3"
                                  class="w-full rounded-lg border-gray-300">{{ old('question_text') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Bobot nilai</label>
                        <input type="number" name="points" required min="0.01" max="100"
                               step="0.01" value="{{ old('points', 20) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    @foreach (['A', 'B', 'C', 'D'] as $index => $label)
                        <div>
                            <label class="mb-1 block text-sm font-medium">Pilihan {{ $label }}</label>
                            <input name="options[{{ $index }}][text]" required
                                   value="{{ old("options.$index.text") }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>
                    @endforeach

                    <div>
                        <label class="mb-1 block text-sm font-medium">Jawaban benar</label>
                        <select name="correct_option" required class="w-full rounded-lg border-gray-300">
                            <option value="0">A</option>
                            <option value="1">B</option>
                            <option value="2">C</option>
                            <option value="3">D</option>
                        </select>
                    </div>

                    <button class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                        Simpan Soal
                    </button>
                </form>
            @endif

            <div class="space-y-4">
                <h3 class="text-lg font-bold">Daftar soal ({{ $exam->questions->count() }})</h3>

                @forelse ($exam->questions as $question)
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="font-semibold">
                            {{ $question->question_order }}. {{ $question->question_text }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">Bobot: {{ $question->points }}</p>

                        <div class="mt-4 space-y-2">
                            @foreach ($question->options as $option)
                                <div class="rounded-lg p-3 text-sm
                                    {{ $option->is_correct ? 'bg-green-50 text-green-800' : 'bg-gray-50 text-gray-700' }}">
                                    {{ $option->option_label }}. {{ $option->option_text }}
                                    @if ($option->is_correct)
                                        <span class="font-semibold">(Jawaban benar)</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-gray-300 p-6 text-gray-500">
                        Belum ada soal.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
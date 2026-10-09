
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Ujian</h2>
            <a href="{{ route('teacher.exams.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                + Buat Ujian
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($exams as $exam)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $exam->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $exam->subject->name }} · {{ $exam->classroom->name }}
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $exam->questions_count }} soal ·
                                {{ $exam->attempts_count }} percobaan
                            </p>
                            <span class="mt-3 inline-block rounded-full px-3 py-1 text-xs font-semibold
                                {{ $exam->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $exam->is_published ? 'Dipublikasikan' : 'Draft' }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('teacher.exams.show', $exam) }}"
                               class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Kelola Soal
                            </a>
                            <a href="{{ route('teacher.exams.results', $exam) }}"
                               class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Hasil Siswa
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <h3 class="font-semibold text-gray-800">Belum ada ujian</h3>
                    <p class="mt-2 text-sm text-gray-500">Buat ujian pertama untuk kelas yang kamu ajar.</p>
                </div>
            @endforelse

            {{ $exams->links() }}
        </div>
    </div>
</x-app-layout>
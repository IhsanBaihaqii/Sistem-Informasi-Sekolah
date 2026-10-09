
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Ujian Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-4 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
            @endif

            @forelse ($exams as $exam)
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <h3 class="text-lg font-bold">{{ $exam->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $exam->subject->name }} · {{ $exam->classroom->name }}
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $exam->questions_count }} soal ·
                                Durasi {{ $exam->duration_minutes ?? 'Tidak dibatasi' }}{{ $exam->duration_minutes ? ' menit' : '' }}
                            </p>
                        </div>

                        <a href="{{ route('student.exams.show', $exam) }}"
                           class="rounded-lg bg-blue-600 px-4 py-2 text-center font-semibold text-white hover:bg-blue-700">
                            Lihat Ujian
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <h3 class="font-semibold">Belum ada ujian tersedia</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Ujian yang dipublikasikan untuk kelasmu akan muncul di sini.
                    </p>
                </div>
            @endforelse

            {{ $exams->links() }}
        </div>
    </div>
</x-app-layout>
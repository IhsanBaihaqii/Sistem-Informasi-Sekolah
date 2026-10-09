<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Ujian</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div>
                <a href="{{ route('student.exams.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar Ujian
                </a>
            </div>

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50/50 p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <span class="inline-flex items-center rounded-lg bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                            </span>
                            <h1 class="mt-2 text-2xl font-bold text-slate-900">{{ $exam->title }}</h1>
                            <p class="mt-1 text-sm text-slate-500">Kelas: {{ $exam->classroom->name ?? '-' }}</p>
                        </div>

                        @if ($attempt)
                            <div class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-semibold
                                {{ $attempt->status === 'graded' || $attempt->status === 'submitted' ? 'border-green-200 bg-green-50 text-green-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                                <i class="fa-solid {{ $attempt->status === 'in_progress' ? 'fa-spinner fa-spin' : 'fa-circle-check' }}"></i>
                                {{ $attempt->status === 'in_progress' ? 'Sedang Dikerjakan' : 'Sudah Selesai' }}
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600">
                                <i class="fa-regular fa-clock"></i>
                                Belum Dimulai
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    @if ($exam->description)
                        <div class="mb-6 rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700">
                            <p class="font-semibold text-slate-900">Petunjuk:</p>
                            <p class="mt-1 whitespace-pre-line">{{ $exam->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">Jumlah Soal</p>
                            <p class="mt-1 text-xl font-bold text-slate-900">{{ $exam->questions()->count() }}</p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">Durasi</p>
                            <p class="mt-1 text-xl font-bold text-slate-900">
                                {{ $exam->duration_minutes ? $exam->duration_minutes . ' mnt' : 'Bebas' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">KKM / Kelulusan</p>
                            <p class="mt-1 text-xl font-bold text-slate-900">{{ $exam->passing_score ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">Batas Waktu</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $exam->end_at ? \Illuminate\Support\Carbon::parse($exam->end_at)->format('d M, H:i') : 'Tidak ada' }}
                            </p>
                        </div>
                    </div>

                    @if ($attempt && in_array($attempt->status, ['submitted', 'graded']))
                        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
                            <p class="text-sm font-medium text-slate-500">Hasil Pengerjaan Anda</p>
                            <p class="mt-2 text-4xl font-extrabold {{ (float)$attempt->score >= (float)($exam->passing_score ?? 0) ? 'text-green-600' : 'text-red-600' }}">
                                {{ $attempt->score ?? 0 }}
                            </p>
                            <p class="mt-2 text-sm font-semibold {{ (float)$attempt->score >= (float)($exam->passing_score ?? 0) ? 'text-green-700' : 'text-red-600' }}">
                                {{ (float)$attempt->score >= (float)($exam->passing_score ?? 0) ? 'Selamat, Anda LULUS Ujian Ini!' : 'Nilai belum mencapai KKM.' }}
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Dikumpulkan pada: {{ $attempt->submitted_at ? \Illuminate\Support\Carbon::parse($attempt->submitted_at)->format('d-m-Y H:i') : '-' }}
                            </p>
                        </div>
                    @endif

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        @if (!$attempt)
                            <form method="POST" action="{{ route('student.exams.start', $exam) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm hover:bg-blue-700 sm:w-auto">
                                    <i class="fa-solid fa-play"></i> Mulai Kerjakan Ujian
                                </button>
                            </form>
                        @elseif ($attempt->status === 'in_progress')
                            <a href="{{ route('student.exams.take', $attempt) }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm hover:bg-blue-700 sm:w-auto">
                                <i class="fa-solid fa-arrow-right"></i> Lanjutkan Ujian
                            </a>
                        @else
                            <a href="{{ route('student.exams.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50 sm:w-auto">
                                Kembali ke Daftar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

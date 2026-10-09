<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">{{ $attempt->exam->title }}</h2>
                <p class="text-xs text-slate-500">{{ $attempt->exam->subject->name }} · {{ $attempt->exam->classroom->name }}</p>
            </div>

            @if ($attempt->exam->duration_minutes || $attempt->exam->end_at)
                @php
                    $durationEnd = $attempt->started_at && $attempt->exam->duration_minutes
                        ? $attempt->started_at->copy()->addMinutes($attempt->exam->duration_minutes)->timestamp
                        : null;
                    $examEnd = $attempt->exam->end_at ? $attempt->exam->end_at->timestamp : null;
                    $targetTimestamp = min(array_filter([$durationEnd, $examEnd]));
                @endphp
                <div class="inline-flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-2 font-mono text-sm font-bold text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-stopwatch text-amber-600"></i>
                    <span id="countdown-timer">Memuat waktu...</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
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

            @php
                $answeredMap = $attempt->answers->pluck('question_option_id', 'question_id')->toArray();
                $totalQuestions = $attempt->exam->questions->count();
                $answeredCount = count(array_filter($answeredMap));
            @endphp

            <!-- Progress bar & question index summary -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-slate-700">
                        Progres: <span class="text-blue-600">{{ $answeredCount }}</span> dari {{ $totalQuestions }} terjawab
                    </p>
                    <span class="text-xs text-slate-500 font-medium">{{ round(($answeredCount / max(1, $totalQuestions)) * 100) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mb-4">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ round(($answeredCount / max(1, $totalQuestions)) * 100) }}%"></div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($attempt->exam->questions as $idx => $q)
                        @php
                            $isDone = isset($answeredMap[$q->id]) && !empty($answeredMap[$q->id]);
                        @endphp
                        <a href="#soal-{{ $q->id }}"
                           class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-bold transition
                           {{ $isDone ? 'bg-blue-600 text-white shadow-sm' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                            {{ $idx + 1 }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- List of Questions -->
            <div class="space-y-6">
                @foreach ($attempt->exam->questions as $qIndex => $question)
                    @php
                        $selectedOptionId = $answeredMap[$question->id] ?? null;
                    @endphp
                    <div id="soal-{{ $question->id }}" class="scroll-mt-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    Soal {{ $qIndex + 1 }}
                                </span>
                                <span class="ml-2 text-xs text-slate-400">({{ $question->points }} poin)</span>
                            </div>

                            @if ($selectedOptionId)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                    <i class="fa-solid fa-check text-[10px]"></i> Sudah dijawab
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                    Belum dijawab
                                </span>
                            @endif
                        </div>

                        <div class="py-4">
                            <p class="whitespace-pre-line text-base font-medium leading-relaxed text-slate-900">
                                {{ $question->question_text }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('student.exams.answer', $attempt) }}" class="space-y-3 pt-2">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question->id }}">

                            <div class="space-y-2.5">
                                @foreach ($question->options as $option)
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border p-3.5 transition
                                        {{ (int)$selectedOptionId === (int)$option->id ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 hover:bg-slate-50' }}">
                                        <input type="radio"
                                               name="question_option_id"
                                               value="{{ $option->id }}"
                                               @checked((int)$selectedOptionId === (int)$option->id)
                                               onchange="this.form.submit()"
                                               class="mt-1 h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <div class="min-w-0 flex-1 text-sm text-slate-800">
                                            <span class="font-bold mr-1">{{ $option->option_label }}.</span>
                                            <span>{{ $option->option_text }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pilihan
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Submit Final Button Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm text-center">
                <h3 class="text-base font-bold text-slate-900">Selesaikan Ujian?</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Pastikan Anda telah memeriksa semua jawaban sebelum mengumpulkan ujian ini.
                </p>

                <form id="submit-exam-form" method="POST" action="{{ route('student.exams.submit', $attempt) }}" class="mt-5">
                    @csrf
                    <button type="button"
                        onclick="if(confirm('Apakah Anda yakin ingin mengumpulkan ujian ini sekarang? Setelah dikumpulkan, Anda tidak dapat mengubah jawaban.')) { document.getElementById('submit-exam-form').submit(); }"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-green-700">
                        <i class="fa-solid fa-paper-plane"></i> Kumpulkan & Selesaikan Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if (isset($targetTimestamp) && $targetTimestamp)
        <script>
            (function() {
                const targetTime = {{ $targetTimestamp }} * 1000;
                const timerElement = document.getElementById('countdown-timer');

                function updateCountdown() {
                    const now = new Date().getTime();
                    const diff = targetTime - now;

                    if (diff <= 0) {
                        timerElement.innerHTML = "Waktu habis!";
                        document.getElementById('submit-exam-form')?.submit();
                        return;
                    }

                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    const pad = (n) => String(n).padStart(2, '0');
                    if (hours > 0) {
                        timerElement.innerText = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                    } else {
                        timerElement.innerText = `${pad(minutes)}:${pad(seconds)}`;
                    }
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            })();
        </script>
    @endif
</x-app-layout>


<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div>
            <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Data Siswa
            </a>
        </div>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-blue-700 px-6 py-8 text-white sm:px-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    @if ($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto siswa"
                            class="h-24 w-24 rounded-2xl border-2 border-white/30 object-cover">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-white/15 text-3xl font-bold">
                            {{ strtoupper(substr($student->full_name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-blue-100">Profil Siswa</p>
                        <h1 class="mt-1 break-words text-2xl font-bold">{{ $student->full_name }}</h1>
                        <p class="mt-2 text-sm text-blue-100">NIS: {{ $student->nis }}</p>
                    </div>

                    <a href="{{ route('admin.students.edit', $student->id) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-50">
                        <i class="fa-solid fa-pen"></i>Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">
                @php
                    $details = [
                        'NIS' => $student->nis,
                        'NISN' => $student->nisn,
                        'Nama lengkap' => $student->full_name,
                        'Jenis kelamin' => $student->gender,
                        'Tempat lahir' => $student->birth_place,
                        'Tanggal lahir' => $student->birth_date ? \Illuminate\Support\Carbon::parse($student->birth_date)->format('d-m-Y') : null,
                        'Agama' => $student->religion,
                        'Nomor telepon' => $student->phone,
                        'Tanggal masuk' => $student->admission_date ? \Illuminate\Support\Carbon::parse($student->admission_date)->format('d-m-Y') : null,
                        'Status' => [
                            'active' => 'Aktif',
                            'inactive' => 'Tidak aktif',
                            'graduated' => 'Lulus',
                            'transferred' => 'Pindah',
                        ][$student->status] ?? $student->status,
                        'Dibuat pada' => $student->created_at ? \Illuminate\Support\Carbon::parse($student->created_at)->format('d-m-Y H:i') : null,
                    ];
                @endphp

                @foreach ($details as $label => $value)
                    <div class="border-b border-slate-100 pb-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
                        <p class="mt-2 break-words text-sm font-semibold text-slate-800">
                            {{ filled($value) ? $value : 'Belum diisi' }}
                        </p>
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Alamat</p>
                    <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-slate-800">
                        {{ $student->address ?: 'Alamat belum diisi.' }}
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
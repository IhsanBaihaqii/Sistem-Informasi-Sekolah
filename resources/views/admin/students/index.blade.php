
<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium text-blue-600">Manajemen Sekolah</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">Data Siswa</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola informasi siswa yang terdaftar di sekolah.</p>
            </div>

            <a href="{{ route('admin.students.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                <i class="fa-solid fa-plus"></i>
                Tambah Siswa
            </a>
        </section>

        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Ada data yang perlu diperbaiki.</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Siswa</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-users"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($totalStudents) }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Siswa Aktif</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <i class="fa-solid fa-user-check"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($activeStudents) }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Status Lainnya</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i class="fa-solid fa-user-clock"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($inactiveStudents) }}</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5 sm:p-6">
                <form action="{{ route('admin.students.index') }}" method="GET"
                    class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_190px_auto_auto]">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, NIS, atau NISN..."
                            class="w-full rounded-xl border border-slate-300 py-3 pl-11 pr-4 text-sm focus:border-blue-500 focus:ring-blue-100">
                    </div>

                    <select name="status"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
                        <option value="">Semua status</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Tidak aktif</option>
                        <option value="graduated" @selected(request('status') === 'graduated')>Lulus</option>
                        <option value="transferred" @selected(request('status') === 'transferred')>Pindah</option>
                    </select>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Cari
                    </button>

                    <a href="{{ route('admin.students.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Siswa</th>
                            <th class="px-6 py-4 font-semibold">NIS / NISN</th>
                            <th class="px-6 py-4 font-semibold">Jenis Kelamin</th>
                            <th class="px-6 py-4 font-semibold">Telepon</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $student)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}"
                                                alt="Foto {{ $student->full_name }}"
                                                class="h-11 w-11 rounded-full border border-slate-200 object-cover">
                                        @else
                                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-50 font-bold text-blue-700">
                                                {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $student->full_name }}</p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $student->birth_place ?: 'Tempat lahir belum diisi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $student->nis }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $student->nisn ?: 'NISN belum diisi' }}</p>
                                </td>

                                <td class="px-6 py-4 text-slate-600">{{ $student->gender }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $student->phone ?: '—' }}</td>

                                <td class="px-6 py-4">
                                    @php
                                        $statusLabels = [
                                            'active' => 'Aktif',
                                            'inactive' => 'Tidak aktif',
                                            'graduated' => 'Lulus',
                                            'transferred' => 'Pindah',
                                        ];

                                        $statusClasses = [
                                            'active' => 'bg-green-50 text-green-700',
                                            'inactive' => 'bg-slate-100 text-slate-600',
                                            'graduated' => 'bg-blue-50 text-blue-700',
                                            'transferred' => 'bg-amber-50 text-amber-700',
                                        ];
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$student->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.show', $student->id) }}"
                                            title="Detail"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            title="Edit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-blue-100 text-blue-700 hover:bg-blue-50">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data {{ addslashes($student->full_name) }}? Data relasi yang memakai cascade juga dapat terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 text-red-600 hover:bg-red-50">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <i class="fa-solid fa-user-graduate text-xl"></i>
                                    </div>
                                    <p class="mt-4 font-semibold text-slate-800">Data siswa tidak ditemukan</p>
                                    <p class="mt-1 text-sm text-slate-500">Coba ubah kata pencarian atau tambahkan data siswa baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                    {{ $students->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
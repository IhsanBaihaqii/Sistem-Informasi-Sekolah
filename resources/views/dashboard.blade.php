<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-2 text-sm font-medium text-blue-600">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Selamat datang, {{ $user->name }}!
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base">
                    {{ $roleDescription }}
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                <i class="fa-solid fa-shield-halved"></i>
                {{ $roleLabel }}
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($dashboardCards as $card)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                {{ $card['label'] }}
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                {{ number_format($card['value']) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                Ringkasan data sistem
                            </p>
                        </div>

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 xl:col-span-2">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Akses Cepat
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Buka menu yang sering digunakan.
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @if (in_array($role, ['super_admin', 'admin']))
                        <a href="{{ route('admin.students.index') }}"
                            class="group flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-user-graduate"></i>
                            </span>

                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Data Siswa</span>
                                <span class="mt-1 block text-xs text-slate-500">Pengelolaan data siswa</span>
                            </span>

                            <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-xs text-slate-400"></i>
                        </a>

                        <a href="{{ Route::has('admin.teachers.index') ? route('admin.teachers.index') : '#' }}"
                           class="group flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Data Guru</span>
                                <span class="mt-1 block text-xs text-slate-500">Pengelolaan data guru</span>
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-xs text-slate-400"></i>
                        </a>
                    @endif

                    @if ($role === 'principal')
                        <a href="{{ url('/admin/reports') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-chart-column"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Laporan Akademik</span>
                                <span class="mt-1 block text-xs text-slate-500">Ringkasan kegiatan sekolah</span>
                            </span>
                        </a>

                        <a href="{{ url('/admin/attendance') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Presensi</span>
                                <span class="mt-1 block text-xs text-slate-500">Pemantauan kehadiran</span>
                            </span>
                        </a>
                    @endif

                    @if ($role === 'teacher')
                        <a href="{{ route('teacher.exams.index') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Kelola Ujian</span>
                                <span class="mt-1 block text-xs text-slate-500">Ujian dan hasil siswa</span>
                            </span>
                        </a>

                        <a href="{{ url('/teacher/assignments') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-file-pen"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Tugas</span>
                                <span class="mt-1 block text-xs text-slate-500">Kelola tugas pembelajaran</span>
                            </span>
                        </a>
                    @endif

                    @if ($role === 'student')
                        <a href="{{ route('student.exams.index') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Ujian Saya</span>
                                <span class="mt-1 block text-xs text-slate-500">Lihat ujian yang tersedia</span>
                            </span>
                        </a>

                        <a href="{{ url('/student/materials') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-book-open"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Pembelajaran</span>
                                <span class="mt-1 block text-xs text-slate-500">Akses materi dan tugas</span>
                            </span>
                        </a>
                    @endif

                    @if ($role === 'parent')
                        <a href="{{ url('/parent/children') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-child"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Informasi Anak</span>
                                <span class="mt-1 block text-xs text-slate-500">Lihat data anak terhubung</span>
                            </span>
                        </a>

                        <a href="{{ url('/parent/grades') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-chart-line"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Akademik Anak</span>
                                <span class="mt-1 block text-xs text-slate-500">Pantau tugas dan nilai</span>
                            </span>
                        </a>
                    @endif

                    @if ($role === 'super_admin')
                        <a href="{{ url('/admin/users') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-users-gear"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Pengguna</span>
                                <span class="mt-1 block text-xs text-slate-500">Kelola akun sistem</span>
                            </span>
                        </a>

                        <a href="{{ url('/admin/roles') }}"
                           class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:bg-blue-50">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Role & Permission</span>
                                <span class="mt-1 block text-xs text-slate-500">Pengaturan hak akses</span>
                            </span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Informasi Akun
                        </h2>
                        <p class="text-xs text-slate-500">Detail sesi saat ini</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-xs text-slate-500">Nama pengguna</p>
                        <p class="mt-1 break-words text-sm font-semibold text-slate-800">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Email</p>
                        <p class="mt-1 break-words text-sm font-semibold text-slate-800">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Role aktif</p>
                        <p class="mt-1 text-sm font-semibold text-blue-700">
                            {{ $roleLabel }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-800">
                            Tips penggunaan
                        </p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Gunakan menu di sebelah kiri untuk membuka fitur yang tersedia bagi akunmu.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

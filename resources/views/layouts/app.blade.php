<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Informasi Sekolah') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen">
        <div
            id="sidebar-overlay"
            class="fixed inset-0 z-40 hidden bg-slate-950/40 lg:hidden"
        ></div>

        <aside
            id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0"
        >
            <div class="flex h-20 items-center gap-3 border-b border-slate-100 px-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <i class="fa-solid fa-school text-xl"></i>
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-slate-900">
                        EduSchool
                    </p>
                    <p class="text-xs text-slate-500">Sistem Informasi Sekolah</p>
                </div>

                <button
                    id="close-sidebar"
                    type="button"
                    class="ml-auto flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden"
                    aria-label="Tutup navigasi"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="border-b border-slate-100 px-5 py-5">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Akun Aktif
                </p>

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="truncate text-xs text-slate-500">
                            {{ auth()->user()->getRoleNames()->first() ?? 'Pengguna' }}
                        </p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
                <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Menu Utama
                </p>

                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-xl bg-blue-50 px-3 py-3 text-sm font-semibold text-blue-700"
                >
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    Dashboard
                </a>

                @if (auth()->user()->hasAnyRole(['super_admin', 'admin']))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Manajemen Sekolah
                    </p>

                    <a href="{{ route('admin.students.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-user-graduate w-5 text-center"></i>
                        Data Siswa
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-chalkboard-user w-5 text-center"></i>
                        Data Guru
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-school w-5 text-center"></i>
                        Kelas
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-book-open w-5 text-center"></i>
                        Mata Pelajaran
                    </a>
                @endif

                @if (auth()->user()->hasRole('principal'))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Pemantauan
                    </p>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-chart-column w-5 text-center"></i>
                        Laporan Akademik
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-clipboard-check w-5 text-center"></i>
                        Laporan Presensi
                    </a>
                @endif

                @if (auth()->user()->hasRole('teacher'))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Pembelajaran
                    </p>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                        Jadwal Mengajar
                    </a>

                    <a href="{{ url('/guru/ujian') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-file-circle-check w-5 text-center"></i>
                        Ujian
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-clipboard-user w-5 text-center"></i>
                        Presensi
                    </a>
                @endif

                @if (auth()->user()->hasRole('student'))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Akademik Saya
                    </p>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                        Jadwal Pelajaran
                    </a>

                    <a href="{{ url('/siswa/ujian') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-file-circle-check w-5 text-center"></i>
                        Ujian
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i>
                        Nilai Saya
                    </a>
                @endif

                @if (auth()->user()->hasRole('parent'))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Informasi Anak
                    </p>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-child w-5 text-center"></i>
                        Data Anak
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i>
                        Perkembangan Akademik
                    </a>
                @endif

                @if (auth()->user()->hasRole('super_admin'))
                    <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Sistem
                    </p>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i>
                        Manajemen Pengguna
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                        Role & Permission
                    </a>
                @endif

                <p class="px-3 pb-2 pt-6 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Akun
                </p>

                @if (Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-user-gear w-5 text-center"></i>
                        Profil Saya
                    </a>
                @endif
            </nav>

            <div class="border-t border-slate-100 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-red-600 hover:bg-red-50"
                    >
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="min-h-screen lg:pl-72">
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        id="open-sidebar"
                        type="button"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden"
                        aria-label="Buka navigasi"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-900 sm:text-base">
                            Sistem Informasi Sekolah
                        </p>
                        <p class="hidden text-xs text-slate-500 sm:block">
                            Portal administrasi dan akademik
                        </p>
                    </div>
                </div>

                <div class="ml-3 flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="max-w-40 truncate text-sm font-semibold text-slate-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs capitalize text-slate-500">
                            {{ str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'pengguna') }}
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 bg-white px-4 py-5 text-center text-xs text-slate-500 sm:px-6">
                &copy; {{ date('Y') }} Sistem Informasi Sekolah. Seluruh hak cipta dilindungi.
            </footer>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openButton = document.getElementById('open-sidebar');
        const closeButton = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openButton?.addEventListener('click', openSidebar);
        closeButton?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>

<x-guest-layout>
    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2">
            <section class="hidden flex-col justify-between bg-blue-700 p-10 text-white lg:flex xl:p-12">
                <div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15">
                        <i class="fa-solid fa-school text-2xl"></i>
                    </div>

                    <p class="mt-8 text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">
                        Portal Akademik
                    </p>

                    <h1 class="mt-4 text-4xl font-bold leading-tight">
                        Sistem Informasi Sekolah
                    </h1>

                    <p class="mt-5 max-w-md text-sm leading-7 text-blue-100">
                        Satu portal untuk mendukung pengelolaan sekolah, pembelajaran,
                        administrasi, dan pemantauan akademik.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-sm text-blue-100">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Akses sesuai peran dan tanggung jawab pengguna</span>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-blue-100">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Informasi akademik terintegrasi</span>
                    </div>

                    <p class="border-t border-white/20 pt-5 text-xs text-blue-100">
                        &copy; {{ date('Y') }} Sistem Informasi Sekolah
                    </p>
                </div>
            </section>

            <section class="p-6 sm:p-10 lg:p-12">
                <div class="mb-8 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <i class="fa-solid fa-school text-xl"></i>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold text-blue-600">
                        SELAMAT DATANG
                    </p>

                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        Masuk ke akun
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Masukkan email dan kata sandi untuk melanjutkan ke dashboard sekolah.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Alamat email
                        </label>

                        <div class="relative">
                            <i class="fa-regular fa-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@sekolah.sch.id"
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                Kata sandi
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800"
                                >
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <i class="fa-solid fa-lock pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >
                        Ingat saya
                    </label>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        <span>Masuk ke Dashboard</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                    Akses sistem hanya untuk pengguna yang memiliki akun.
                    Hubungi administrator sekolah jika mengalami kendala.
                </p>
            </section>
        </div>
    </main>
</x-guest-layout>
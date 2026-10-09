
<x-app-layout>
    <div class="mx-auto max-w-5xl">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Data Siswa
            </a>
            <h1 class="mt-4 text-2xl font-bold text-slate-900">Tambah Siswa</h1>
            <p class="mt-1 text-sm text-slate-500">Lengkapi informasi siswa di bawah ini.</p>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
            @csrf
            @include('admin.students._form')
        </form>
    </div>
</x-app-layout>
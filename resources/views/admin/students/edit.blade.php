
<x-app-layout>
    <div class="mx-auto max-w-5xl">
        <div class="mb-6">
            <a href="{{ route('admin.students.show', $student->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Detail Siswa
            </a>
            <h1 class="mt-4 text-2xl font-bold text-slate-900">Edit Data Siswa</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui data {{ $student->full_name }}.</p>
        </div>

        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
            @csrf
            @method('PUT')
            @include('admin.students._form')
        </form>
    </div>
</x-app-layout>
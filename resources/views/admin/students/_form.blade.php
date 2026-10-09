@php
    $editing = isset($student);
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="nis" class="mb-2 block text-sm font-semibold text-slate-700">NIS *</label>
        <input id="nis" name="nis" value="{{ old('nis', $student->nis ?? '') }}"
            required maxlength="30"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('nis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="nisn" class="mb-2 block text-sm font-semibold text-slate-700">NISN</label>
        <input id="nisn" name="nisn" value="{{ old('nisn', $student->nisn ?? '') }}"
            maxlength="30"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('nisn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="full_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama lengkap *</label>
        <input id="full_name" name="full_name"
            value="{{ old('full_name', $student->full_name ?? '') }}"
            required maxlength="150"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="gender" class="mb-2 block text-sm font-semibold text-slate-700">Jenis kelamin *</label>
        <select id="gender" name="gender" required
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
            <option value="">Pilih jenis kelamin</option>
            <option value="Laki-laki" @selected(old('gender', $student->gender ?? '') === 'Laki-laki')>Laki-laki</option>
            <option value="Perempuan" @selected(old('gender', $student->gender ?? '') === 'Perempuan')>Perempuan</option>
        </select>
        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status *</label>
        <select id="status" name="status" required
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
            @foreach (['active' => 'Aktif', 'inactive' => 'Tidak aktif', 'graduated' => 'Lulus', 'transferred' => 'Pindah'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $student->status ?? 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="birth_place" class="mb-2 block text-sm font-semibold text-slate-700">Tempat lahir</label>
        <input id="birth_place" name="birth_place"
            value="{{ old('birth_place', $student->birth_place ?? '') }}" maxlength="100"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('birth_place') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="birth_date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal lahir</label>
        <input id="birth_date" type="date" name="birth_date"
            value="{{ old('birth_date', isset($student->birth_date) ? \Illuminate\Support\Str::substr($student->birth_date, 0, 10) : '') }}"
            max="{{ date('Y-m-d') }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="religion" class="mb-2 block text-sm font-semibold text-slate-700">Agama</label>
        <input id="religion" name="religion"
            value="{{ old('religion', $student->religion ?? '') }}" maxlength="30"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('religion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Nomor telepon</label>
        <input id="phone" name="phone"
            value="{{ old('phone', $student->phone ?? '') }}" maxlength="30"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="admission_date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal masuk</label>
        <input id="admission_date" type="date" name="admission_date"
            value="{{ old('admission_date', isset($student->admission_date) ? \Illuminate\Support\Str::substr($student->admission_date, 0, 10) : '') }}"
            max="{{ date('Y-m-d') }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">
        @error('admission_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
        <textarea id="address" name="address" rows="3"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-100">{{ old('address', $student->address ?? '') }}</textarea>
        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="photo" class="mb-2 block text-sm font-semibold text-slate-700">Foto siswa</label>

        @if ($editing && $student->photo)
            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto siswa"
                class="mb-3 h-20 w-20 rounded-xl border border-slate-200 object-cover">
        @endif

        <input id="photo" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
            class="block w-full rounded-xl border border-slate-300 bg-white text-sm file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:font-semibold file:text-blue-700">
        <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, WEBP. Maksimal 2 MB.</p>
        @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
    <a href="{{ route('admin.students.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>

    <button type="submit"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
        <i class="fa-solid fa-floppy-disk"></i>
        {{ $editing ? 'Simpan Perubahan' : 'Simpan Siswa' }}
    </button>
</div>
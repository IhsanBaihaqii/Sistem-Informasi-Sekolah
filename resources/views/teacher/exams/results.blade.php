
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Hasil Ujian: {{ $exam->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Siswa</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Waktu kumpul</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nilai</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($attempts as $attempt)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                        {{ $attempt->student->full_name }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $attempt->submitted_at?->format('d-m-Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm font-semibold text-gray-900">
                                        {{ $attempt->score ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $attempt->status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500">
                                        Belum ada siswa yang mengumpulkan ujian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $attempts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
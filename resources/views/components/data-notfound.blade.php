{{-- resources/views/components/empty-state.blade.php --}}
@props([
    'colspan' => 9,
    'message' => 'Tidak Ada Data Yang Ditemukan.',
    'image' => 'images/not-found.png',
])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-2 text-center text-sm text-gray-500">
        <div class="flex flex-col items-center justify-center">
            <img src="{{ asset($image) }}" alt="Data tidak ditemukan"
                class="w-60 h-60 mb-4 opacity-90">
            <p class="text-red-600 text-sm">{{ $message }}</p>
        </div>
    </td>
</tr>

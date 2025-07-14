@props([
    'action' => request()->url(),
    'name' => 'search',
    'value' => request()->get('search'),
    'placeholder' => 'Cari...',
])

<form id="searchForm" method="GET" action="{{ $action }}" class="relative w-full md:w-2/3">
    {{-- Menyertakan filter_tanggal agar tetap dikirim saat search --}}
    <input type="hidden" name="filter_tanggal" value="{{ request('filter_tanggal') }}">

    <input
        type="text"
        name="{{ $name }}"
        id="searchInput"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="border px-3 py-2 rounded w-full pr-10"
        autocomplete="off"
    >

    {{-- Tombol X untuk clear --}}
    @if (!empty($value))
        <button
            type="button"
            id="clearSearch"
            class="absolute right-2 top-1.5 text-gray-500 hover:text-red-600 focus:outline-none text-2xl cursor-pointer"
        >
            &times;
        </button>
    @endif
</form>


@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');
                const clearBtn = document.getElementById('clearSearch');

                let typingTimer;
                const delay = 1000;

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(() => {
                            searchForm.submit();
                        }, delay);
                    });
                }

                if (clearBtn) {
                    clearBtn.addEventListener('click', function () {
                        searchInput.value = '';
                        searchForm.submit();
                    });
                }
            });
        </script>
    @endpush
@endonce


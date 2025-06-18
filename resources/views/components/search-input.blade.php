@props([
    'action' => request()->url(),
    'name' => 'search',
    'value' => request()->get('search'),
    'placeholder' => 'Cari...',
])

<form id="searchForm" method="GET" action="{{ $action }}">
    <input type="text"
        name="{{ $name }}"
        id="searchInput"
        value="{{ request('search') }}"
        placeholder="{{ $placeholder }}"
        class="border px-3 py-2 rounded w-full md:w-2/3"
        autocomplete="off"
        >
</form>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');

                let typingTimer;
                const delay = 500;

                searchInput.addEventListener('input', function () {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        searchForm.submit();
                    }, delay);
                });
            });
        </script>
    @endpush
@endonce

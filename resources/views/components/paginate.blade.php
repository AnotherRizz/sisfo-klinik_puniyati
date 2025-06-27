<form id="paginationForm" method="GET" action="{{ $action ?? url()->current() }}" class="flex items-center mb-4 text-sm">
    @foreach (request()->except('per_page', 'page') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <label for="rowsPerPage" class="mr-2 text-slate-500">Lihat</label>
    <select name="per_page" id="rowsPerPage" class="border rounded-lg px-6 py-1 text-slate-500"
        onchange="document.getElementById('paginationForm').submit();">
        @foreach ($options as $option)
            <option value="{{ $option }}" {{ request('per_page', $default ?? 10) == $option ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    <span class="ml-2 text-slate-500">baris</span>
</form>

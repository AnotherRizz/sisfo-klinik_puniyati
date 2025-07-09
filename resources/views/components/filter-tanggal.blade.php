@php
    $selectedValue = $selected ?? 'hari_ini';
@endphp

<select id="{{ $id ?? 'filterTanggal' }}" name="{{ $name ?? 'filter_tanggal' }}" class="{{ $class ?? 'border-gray-300 rounded px-7 py-2 text-sm' }}">
    <option value="semua" {{ $selectedValue == 'semua' ? 'selected' : '' }}>Semua</option>
    <option value="hari_ini" {{ $selectedValue == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
</select>

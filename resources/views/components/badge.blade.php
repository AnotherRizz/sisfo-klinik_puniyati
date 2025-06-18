@props(['label', 'color' => 'blue'])

<span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-{{ $color }}-500 rounded-full shadow-sm">
    {{ $label }}
</span>

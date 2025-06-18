@props(['label', 'value'])

<div>
    <h3 class="font-semibold text-gray-600">{{ $label }}</h3>
    <p class="text-gray-800">{{ $value ?? '-' }}</p>
</div>

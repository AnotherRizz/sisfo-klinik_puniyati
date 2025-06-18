@props([
    'id',
    'name',
    'label' => '',
    'options' => [],
    'selected' => [],
    'placeholder' => '-- Pilih --',
    'multiple' => false
])

@php
    $isMultiple = $multiple ? 'multiple' : '';
    $nameAttr = $multiple ? $name . '[]' : $name;
    $selectedItems = is_array($selected) ? $selected : [$selected];
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" name="{{ $nameAttr }}"
        {{ $attributes->merge(['class' => 'select2 w-full border border-gray-300 rounded px-3 py-2']) }}
        {{ $isMultiple }}>
        @if (!$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $value)
            @php
                $labelOption = is_array($value) ? ($value['label'] ?? $key) : $value;
                $attrs = is_array($value) ? \Illuminate\Support\Arr::except($value, ['label']) : [];
            @endphp
            <option value="{{ $key }}" {{ in_array($key, $selectedItems) ? 'selected' : '' }}
                @foreach ($attrs as $attr => $val)
                    {{ $attr }}="{{ $val }}"
                @endforeach
            >{{ $labelOption }}</option>
        @endforeach
    </select>
</div>

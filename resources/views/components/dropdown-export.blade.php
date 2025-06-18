@props([
    'buttonId' => 'dropdownDefaultButton',
    'dropdownId' => 'dropdown',
    'label' => 'Export',
    'items' => [
        ['label' => 'Export Pdf', 'href' => '#'],
        ['label' => 'Export Csv', 'href' => '#'],
    ],
])

<div class="relative inline-block">
    <button id="{{ $buttonId }}" data-dropdown-toggle="{{ $dropdownId }}"
        class="text-white cursor-pointer bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-xs px-3 py-2 text-center inline-flex items-center dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800"
        type="button">
        {{ $label }}
        <svg class="w-1.5 h-1 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
        </svg>
    </button>

    <div id="{{ $dropdownId }}"
        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $buttonId }}">
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

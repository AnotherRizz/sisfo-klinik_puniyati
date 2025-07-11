<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', config('app.name', 'KlinikApp'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">


    @vite('resources/css/app.css')

    <!-- Optional: Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom styling biar lebih modern */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            /* Tailwind border-gray-300 */
            border-radius: 0.5rem;
            /* rounded-lg */
            padding: 0.4rem;
            min-height: 45px;
        }

        .select2-results__options {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
.select2-dropdown-scroll {
        max-height: 250px !important;
        overflow-y: auto !important;
    }


        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f3f4f6;
            /* Tailwind bg-gray-100 */
            color: #111827;
            /* Tailwind text-gray-900 */
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 2px 8px;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            /* text-sm */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ef4444;
            /* Tailwind text-red-500 */
            margin-right: 1px;
        }
    </style>


</head>

<body class="font-poppins bg-neutral-100 min-h-screen">

    <x-sidebar />
    {{-- Main Content --}}
    <main class=" p-4 sm:ml-64 mt-8 md:mt-14">

        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>


    <!-- Flash Message Alert -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            });
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
            });
        </script>
    @endif
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: 'Cari data...',
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush

    <script src="//unpkg.com/alpinejs" defer></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-4"></div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>

</html>

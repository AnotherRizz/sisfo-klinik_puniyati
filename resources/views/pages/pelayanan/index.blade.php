@extends('layouts.app')

@section('title', 'Master Data Pelayanan')

@section('content')
    <h1 class="text-xl font- mb-4">Data Pelayanan</h1>

    <div class="flex w-full gap-2 items-center justify-end">
        <a href="{{ route('pelayanan.export') }}"target="_blank"            
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Export
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>
      


        <a href="{{ route('pelayanan.create') }}"
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Pelayanan
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
            </svg>


        </a>
    </div>



    <div class="bg-white rounded shadow p-2">
        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('pelayanan.index')" name="search" placeholder="Cari nama / kode pelayanan..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('pelayanan.index')" />

        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                        Pelayanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        Pelayanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelayanans as $pelayanan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($pelayanans->currentPage() - 1) * $pelayanans->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pelayanan->kodpel }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pelayanan->nama_pelayanan }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('pelayanan.edit', $pelayanan->id) }}"
                                class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>
                            {{-- <form id="delete-form-{{ $pelayanan->id }}"
                                action="{{ route('pelayanan.destroy', $pelayanan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="px-3 py-1 cursor-pointer text-white bg-red-500 rounded hover:bg-red-600"
                                    onclick="confirmDelete({{ $pelayanan->id }})">
                                    Hapus
                                </button>
                            </form> --}}

                        </td>
                    </tr>
                 @empty
                    <x-data-notfound :colspan="9" message="Data pelayanan tidak ditemukan"  />
                @endforelse
            </tbody>
        </table>
        @if ($pelayanans->hasPages())
            <nav aria-label="Page navigation" class="mt-4  m-2 mb-3">
                <ul class="inline-flex -space-x-px text-sm">
                    {{-- Tombol Previous --}}
                    @if ($pelayanans->onFirstPage())
                        <li>
                            <span
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-400 bg-white border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed">Previous</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $pelayanans->previousPageUrl() }}"
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                        </li>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($pelayanans->getUrlRange(1, $pelayanans->lastPage()) as $page => $url)
                        <li>
                            @if ($page == $pelayanans->currentPage())
                                <span
                                    class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($pelayanans->hasMorePages())
                        <li>
                            <a href="{{ $pelayanans->nextPageUrl() }}"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">Next</a>
                        </li>
                    @else
                        <li>
                            <span
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-400 bg-white border border-gray-300 rounded-e-lg cursor-not-allowed">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif


    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

@endsection

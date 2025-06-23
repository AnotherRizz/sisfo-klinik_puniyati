@extends('layouts.app')

@section('title', 'Master Data Pasien')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pasien</h1>

    <div class="flex w-full gap-2 items-center justify-end">
        <a href="{{ route('pasien.export') }}" target="_blank"            
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Export
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>
        <a href="{{ route('pasien.create') }}"
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Pasien
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>


        </a>
    </div>



    <div class="bg-white rounded shadow p-2">
        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('pasien.index')" name="search" placeholder="Cari nama / no rekam medis..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('pasien.index')" />

        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pasiens as $pasien)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($pasiens->currentPage() - 1) * $pasiens->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pasien->no_rm }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pasien->nama_pasien }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pasien->nik_pasien }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pasien->alamat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('pasien.edit', $pasien->id) }}"
                                class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>

                            {{-- <form id="delete-form-{{ $pasien->id }}" action="{{ route('pasien.destroy', $pasien->id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="px-3 py-1 cursor-pointer text-white bg-red-500 rounded hover:bg-red-600"
                                    onclick="confirmDelete({{ $pasien->id }})">
                                    Hapus
                                </button>
                            </form> --}}
                            <a href="{{ route('pasien.cetak.kib', $pasien->id) }}" target="_blank"
                                class="px-3 py-1 text-white bg-teal-500 rounded hover:bg-teal-600">Cetak KIB</a>


                        </td>
                    </tr>
                @empty
                    <x-data-notfound :colspan="9" message="Data pasien tidak ditemukan"  />
                @endforelse
            </tbody>
        </table>
        @if ($pasiens->hasPages())
            <nav aria-label="Page navigation" class="mt-4  m-2 mb-3">
                <ul class="inline-flex -space-x-px text-sm">
                    {{-- Tombol Previous --}}
                    @if ($pasiens->onFirstPage())
                        <li>
                            <span
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-400 bg-white border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed">Previous</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $pasiens->previousPageUrl() }}"
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                        </li>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($pasiens->getUrlRange(1, $pasiens->lastPage()) as $page => $url)
                        <li>
                            @if ($page == $pasiens->currentPage())
                                <span
                                    class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($pasiens->hasMorePages())
                        <li>
                            <a href="{{ $pasiens->nextPageUrl() }}"
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

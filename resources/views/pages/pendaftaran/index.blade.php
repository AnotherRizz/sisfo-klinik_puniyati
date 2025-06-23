@extends('layouts.app')

@section('title', 'Data Pendaftaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pendaftaran</h1>

    <div class="flex w-full gap-2 items-center justify-end px-2  rounded-md my-3">

       


        <a href="{{ route('pdf.export') }}"target="_blank"
            class="px-3 py-1.5 text-white flex gap-2 items-center mt-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Export
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>



        <a href="{{ route('pendaftaran.create') }}"
            class="px-3 py-1.5 text-white flex items-center gap-2 mt-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Pendaftaran
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>



        </a>

    </div>


    {{-- ALPINE.JS SEARCH FILTER --}}
    <div class="bg-white rounded shadow p-2 overflow-x-auto">

        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('pendaftaran.index')" name="search" placeholder="Cari nama / no rekam medis..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('pendaftaran.index')" />

        </div>



        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No Reg</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Bidan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Daftar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelayanan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kunjungan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendaftarans as $i => $item)
                    <tr>

                        <td class="px-4 py-2 text-sm text-gray-900">{{ $i+ 1 }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->noreg }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pasien->nama_pasien ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->bidan->nama_bidan ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tgl_daftar)->locale('id')->translatedFormat('d F Y') }}

                        </td>
                        
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pelayanan->nama_pelayanan ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->jenis_kunjungan }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900 flex gap-1">
                            <a href="{{ route('pendaftaran.edit', $item->id) }}"
                                class="px-3 py-1 text-white bg-yellow-500 rounded text-xs hover:bg-yellow-600">Edit</a>
                            {{-- <form id="delete-form-{{ $item->id }}"
                                action="{{ route('pendaftaran.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="px-3 py-1 text-white bg-red-500 rounded text-xs hover:bg-red-600"
                                    onclick="confirmDelete({{ $item->id }})">Hapus</button>
                            </form> --}}
                        </td>
                    </tr>
                @empty
                    <x-data-notfound :colspan="9" />
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pendaftarans->links() }}
        </div>
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

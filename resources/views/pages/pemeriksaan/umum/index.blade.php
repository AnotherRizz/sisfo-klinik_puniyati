@extends('layouts.app')

@section('title', 'Data Pemeriksaan')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pemeriksaan Umum</h1>

    <div class="flex w-full gap-2 justify-end items-center ">
        <a  href="{{ route('all.export', ['nama_pelayanan' => 'Umum']) }}" target="_blank"
            class="px-3 py-1.5 text-white flex items-center justify-center mt-2 gap-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Cetak
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>

        <a href="{{ route('umum.create') }}"
            class="px-3 py-1.5 text-white flex items-center gap-2 mt-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Pemeriksaan
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
            </svg>


        </a>
    </div>
    {{-- ALPINE.JS SEARCH FILTER --}}
    <div x-data="{ search: '' }" class="bg-white rounded shadow p-2 overflow-x-auto">

        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('umum.index')" name="search" placeholder="Cari nama / no periksa..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('umum.index')" />

        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No Periksa</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No RM</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Bidan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelayanan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pemeriksaans as $i => $item)
                    <tr
                        x-show="{{ json_encode(strtolower($item->pasien->nama_pasien ?? '')) }}.includes(search.toLowerCase())">

                        <td class="px-4 py-2 text-sm text-gray-900">{{ $i+1 }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->nomor_periksa }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pendaftaran->pasien->no_rm ?? '-' }}
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pendaftaran->pasien->nama_pasien ?? '-' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pendaftaran->pelayanan->nama_pelayanan ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tgl_kembali)->locale('id')->translatedFormat('d F Y') }}

                        </td>



                        <td class="px-4 py-2 text-sm text-gray-900 flex flex-row gap-1">
                            

                                <a href="{{ route('umum.edit', $item->id) }}"
                                    class="px-3 py-1 text-white bg-yellow-500 rounded text-xs hover:bg-yellow-600">Edit</a>
                                {{-- <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('pemeriksaan.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="px-3 py-1 text-white bg-red-500 rounded text-xs hover:bg-red-600"
                                        onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                </form> --}}
                            {{-- </div>

                            <div> --}}

                                <a href="{{ route('umum.show', $item->id) }}"
                                    class="px-3 py-1 text-white bg-sky-500 rounded text-xs hover:bg-sky-600">Detail</a>
                                <a href="{{ route('umum.resume', $item->id) }}" target="_blank"
                                    class="px-3 py-1 text-white bg-teal-500 rounded text-xs hover:bg-teal-600">Resume</a>
                           
                        </td>
                    </tr>
                @empty
                    <x-data-notfound :colspan="9" />
                @endforelse

            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pemeriksaans->links() }}
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

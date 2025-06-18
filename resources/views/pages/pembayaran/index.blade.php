@extends('layouts.app')

@section('title', 'Data Pembayaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pembayaran</h1>
    <div class="flex w-full gap-2 justify-end items-center ">
        <a href="{{ route('pmb.export') }}"target="_blank"
            class="px-3 py-1.5 text-white flex items-center justify-center mt-2 gap-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Export
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>

        <a href="{{ route('pembayaran.create') }}"
            class="px-3 py-1.5 text-white flex gap-2 mt-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Pembayaran
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>

        </a>
    </div>

    <div x-data="{ search: '' }" class="bg-white rounded shadow p-2 overflow-x-auto">

        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('pembayaran.index')" name="search" placeholder="Cari nama / kode Transaksi..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('pembayaran.index')" />

        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode Transaksi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Bidan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Biaya Administrasi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Bayar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pembayarans as $item)
                    <tr
                        x-show="{{ json_encode(strtolower($item->pasien->nama_pasien ?? '')) }}.includes(search.toLowerCase())">

                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->kd_bayar }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->biaya_administrasi ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->jenis_bayar ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}

                        </td>



                        <td class="px-4 py-2 text-sm text-gray-900 flex flex-col gap-1">
                            <div>

                                <a href="{{ route('pembayaran.edit', $item->id) }}"
                                    class="px-3 py-1 text-white bg-yellow-500 rounded text-xs hover:bg-yellow-600">Edit</a>
                                {{-- <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('pembayaran.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="px-3 py-1 text-white bg-red-500 rounded text-xs hover:bg-red-600"
                                        onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                </form> --}}
                                {{-- </div>
                        
                            <div> --}}

                                <a href="{{ route('pembayaran.show', $item->id) }}"
                                    class="px-3 py-1 text-white bg-sky-500 rounded text-xs hover:bg-sky-600">Detail</a>
                                <a href="{{ route('pmb.bukti-bayar', $item->id) }}" target="_blank"
                                    class="px-3 py-1 text-white bg-teal-500 rounded text-xs hover:bg-teal-600">Bukti</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-notfound :colspan="9" />
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4 bg-white">
            {{ $pembayarans->links() }}
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

@extends('layouts.app')

@section('title', 'Data Pembayaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pembayaran</h1>
    <div>
        <h1 class="text-lg font-bold text-slate-500">
            @if (request('filter_tanggal') == 'semua')
                Menampilkan Semua Pembayaran
            @else
                Menampilkan Pembayaran Tanggal {{ now()->locale('id')->translatedFormat('d F Y') }}
            @endif
        </h1>
    </div>



    <div x-data="{ search: '' }" class="bg-white rounded shadow p-2 overflow-x-auto">
        <div class="flex justify-end my-3 items-center gap-2">
            <x-filter-tanggal id="filterTanggal" name="filter_tanggal" class="border-gray-300 rounded px-7 py-2 text-sm"
                :selected="request('filter_tanggal')" />

        </div>

        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('pembayaran.index')" name="search" placeholder="Cari nama, alamat, tanggal lahir, nomor rm" />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('pembayaran.index')" />

        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No Periksa</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No. RM</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pembayaran</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pembayarans as $item)
                    @php
                        $pembayaran = $item->pembayaran;
                        $pasien = $item->pendaftaran->pasien ?? null;
                        $status = $pembayaran ? 'sudah bayar' : 'belum bayar';
                    @endphp
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->nomor_periksa ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $pasien->no_rm ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $pasien->status .'. '.$pasien->nama_pasien ?? '-' }}</td>
                         <td class="px-4 py-2 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $pasien->alamat ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $pembayaran ? \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @if ($status == 'sudah bayar')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Sudah Bayar
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-sm text-gray-900">
                            @if ($pembayaran)
                                <a href="{{ route('pembayaran.show', $pembayaran->id) }}"
                                    class="px-2 py-1 text-xs bg-sky-500 mb-1 text-white rounded">Detail</a>
                                <a href="{{ route('pmb.bukti-bayar', $pembayaran->id) }}" target="_blank"
                                    class="px-2 py-1 text-xs bg-teal-500 mb-1 text-white rounded">Bukti</a>
                            @else
                                <a href="{{ route('pembayaran.create', ['nomor_periksa' => $item->nomor_periksa]) }}"
                                    class="px-3 py-1 text-xs bg-orange-600 text-white rounded hover:bg-orange-700">Bayar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <x-data-notfound :colspan="7" />
                @endforelse
            </tbody>
        </table> 

        <div class="mt-4 bg-white">
            {{ $pembayarans->links() }}
        </div>

    </div>




    <script>
        document.getElementById('filterTanggal').addEventListener('change', function() {
            const selectedValue = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('filter_tanggal', selectedValue);
            window.location.search = urlParams.toString();
        });
    </script>
@endsection

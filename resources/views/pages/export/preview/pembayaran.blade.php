@extends('layouts.app')

@section('title', 'Laporan Data Pembayaran')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="flex justify-between mb-3">
            <a href="{{ route('laporan.index') }}"
                class="bg-none flex gap-2 items-center border border-slate-300 hover:bg-slate-300 text-slate-700 text-sm px-4 py-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Laporan
            </a>
            <a href="{{ route('laporan.pembayaran', ['bulan' => "$tahun-$bulan", 'jenis_pelayanan' => request('jenis_pelayanan')]) }}"
                class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded">
                Download PDF
            </a>

        </div>

        <div class="bg-white p-6 rounded-lg shadow">

            {{-- Header Logo dan Info --}}
            <div class="flex items-start gap-4 mb-4 relative">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                    class="w-30 h-30 absolute  -top-7 left-32 object-contain">
                <div class="text-center flex-1">
                    <h1 class="text-lg font-bold uppercase">Bidan Praktik Mandiri Puniyati A.Md Keb</h1>
                    <p class="text-sm">Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
                    <p class="text-sm">Dusun Kalipejang RT01/RW07, Desa Demakan, Kec. Mojolaban, Kab. Sukoharjo</p>
                </div>
            </div>

            <div class="border-t-2 border-black my-2"></div>

            <div class="flex justify-start items-center mb-4">
                <h2 class="text-base font-semibold uppercase">Laporan Data Pembayaran</h2>

            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-300">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-2 py-1 text-xs text-center">No</th>
                            <th class="border px-2 py-1 text-xs text-center">No. Pembayaran</th>
                            <th class="border px-2 py-1 text-xs text-center">No. Periksa</th>
                            <th class="border px-2 py-1 text-xs text-center">No. Reg</th>
                            <th class="border px-2 py-1 text-xs text-center">Nama Pasien</th>
                            <th class="border px-2 py-1 text-xs text-center">Nama Bidan</th>
                            <th class="border px-2 py-1 text-xs text-center">Jenis Pelayanan</th>
                            <th class="border px-2 py-1 text-xs text-center">Biaya Obat</th>
                            <th class="border px-2 py-1 text-xs text-center">Biaya Tindakan</th>
                            <th class="border px-2 py-1 text-xs text-center">Total Bayar</th>
                            <th class="border px-2 py-1 text-xs text-center">Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembayarans as $i => $item)
                            <tr class="border-b">
                                <td class="border px-2 py-1 text-xs text-center">{{ $i + 1 }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->kd_bayar ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->pemeriksaan->no_periksa ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->pemeriksaan->pendaftaran->noreg ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ $item->pemeriksaan->pendaftaran->pelayanan->nama_pelayanan ?? '-' }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">Rp{{ number_format($item->pemeriksaan->obat->harga_jual, 0, ',', '.') }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">Rp{{ number_format($item->biaya_tindakan, 0, ',', '.') }}</td>
                                <td  class="border px-2 py-1 text-xs text-center">Rp{{ number_format($item->biaya_tindakan + $item->pemeriksaan->obat->harga_jual, 0, ',', '.') }}
                                </td>
                                <td  class="border px-2 py-1 text-xs text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-right text-xs text-gray-600 mt-6">
                Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
            </div>
        </div>
    </div>
@endsection

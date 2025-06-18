@extends('layouts.app')

@section('title', 'Laporan Data Pendaftaran')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="flex justify-between mb-3">
            <a href="{{ route('laporan.index') }}"
                class="bg-none flex gap-2 items-center border border-slate-300 hover:bg-slate-300 text-slate-700 text-sm px-4 py-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Pemeriksaan
            </a>
            <a href="{{ route('laporan.pemeriksaan', ['bulan' => "$tahun-$bulan", 'jenis_pelayanan' => request('jenis_pelayanan')]) }}"
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
                <h2 class="text-base font-semibold uppercase">Laporan Data Pemeriksaan</h2>

            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-300">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border text-xs px-2 py-1 text-center">No</th>
                            <th class="border text-xs px-2 py-1 text-center">No. Periksa</th>
                            <th class="border text-xs px-2 py-1 text-center">No. Reg</th>
                            <th class="border text-xs px-2 py-1 text-center">No. RM</th>
                            <th class="border text-xs px-2 py-1 text-center">Nama Pasien</th>
                            <th class="border text-xs px-2 py-1 text-center">Tgl Reg</th>
                            <th class="border text-xs px-2 py-1 text-center">Kd Bidan</th>
                            <th class="border text-xs px-2 py-1 text-center">Kd Pelayanan</th>
                            <th class="border text-xs px-2 py-1 text-center">Keluhan</th>
                            <th class="border text-xs px-2 py-1 text-center">Riwayat Penyakit</th>
                            <th class="border text-xs px-2 py-1 text-center">Diagnosa</th>
                            <th class="border text-xs px-2 py-1 text-center">Tindakan</th>
                            <th class="border text-xs px-2 py-1 text-center">Kd Obat</th>
                            <th class="border text-xs px-2 py-1 text-center">Nama Obat</th>
                            <th class="border text-xs px-2 py-1 text-center">Dosis Carkai</th>
                            <th class="border text-xs px-2 py-1 text-center">Tgl Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemeriksaans as $index => $item)
                            <tr class="border-b">
                                <td class="border px-2 py-1 text-center text-xs">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->no_periksa ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->pendaftaran->noreg ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->pendaftaran->pasien->no_rm ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ \Carbon\Carbon::parse($item->pendaftaran->tgl_daftar)->format('d-m-Y') }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->pendaftaran->bidan->kd_bidan ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->pendaftaran->pelayanan->kodpel ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->keluhan ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->riw_penyakit ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->diagnosa ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->tindakan ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->obat->kd_obat ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->obat->nama_obat ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->dosis_carkai ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center text-xs">{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d-m-Y') : '-' }}
                                </td>
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

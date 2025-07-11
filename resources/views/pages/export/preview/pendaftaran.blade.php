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
                Kembali ke Laporan
            </a>
            <form action="{{ route('laporan.pendaftaran') }}" method="GET">
                <input type="hidden" name="filter_type" value="{{ request('filter_type') }}">

                @if (request('filter_type') === 'hari')
                    <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                @elseif(request('filter_type') === 'rentang')
                    <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                @elseif(request('filter_type') === 'bulan')
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                @endif

                <button type="submit" class="bg-red-600 cursor-pointer hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                    Unduh PDF
                </button>
            </form>

        </div>

        <div class="bg-white p-6 rounded-lg shadow">

            {{-- Header Logo dan Info --}}
            <div class="flex items-start gap-4 mb-4 relative">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                    class="w-30 h-30 absolute  -top-7 left-16 object-contain">
                <div class="text-center flex-1">
                    <h1 class="text-lg font-bold uppercase">Bidan Praktik Mandiri Puniyati A.Md Keb</h1>
                    <p class="text-sm">Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
                    <p class="text-sm">Dusun Kalipejang RT01/RW07, Desa Demakan, Kec. Mojolaban, Kab. Sukoharjo</p>
                </div>
            </div>

            <div class="border-t-2 border-black my-2"></div>



            <div class="flex justify-start items-center mb-4">
                <h2 class="text-base font-semibold uppercase">Laporan Data Pendaftaran</h2>

            </div>
            @if ($filterType === 'hari')
                <p>Laporan Pendaftaran Tanggal {{ \Carbon\Carbon::parse($request->tanggal)->translatedFormat('d F Y') }}</p>
            @elseif ($filterType === 'rentang')
                <p>Laporan Pendaftaran Dari {{ \Carbon\Carbon::parse($request->tanggal_awal)->translatedFormat('d F Y') }}
                    sampai {{ \Carbon\Carbon::parse($request->tanggal_akhir)->translatedFormat('d F Y') }}</p>
            @elseif ($filterType === 'bulan')
                <p>Laporan Pendaftaran Bulan
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F Y') }}</p>
            @endif

            @php
                use Carbon\Carbon;
                $urutanPelayanan = ['Umum', 'Kesehatan Ibu Hamil', 'Kesehatan Anak', 'Ibu Nifas', 'KB'];
                $counter = 1;
            @endphp


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm text-left border border-gray-300">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-2 py-1 text-center">No</th>
                            <th class="border px-2 py-1 text-center">No. Reg</th>
                            <th class="border px-2 py-1 text-center">No. RM</th>
                            <th class="border px-2 py-1 text-center">Nama Pasien</th>
                            <th class="border px-2 py-1 text-center">Kode Bidan</th>
                            <th class="border px-2 py-1 text-center">Nama Bidan</th>
                            <th class="border px-2 py-1 text-center">Tanggal Daftar</th>
                            <th class="border px-2 py-1 text-center">Jam Daftar</th>
                            <th class="border px-2 py-1 text-center">Kode Pelayanan</th>
                            <th class="border px-2 py-1 text-center">Jenis Pelayanan</th>
                            <th class="border px-2 py-1 text-center">Jenis Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($urutanPelayanan as $pelayananName)
                            @if (isset($pendaftaransByPelayanan[$pelayananName]))
                                @foreach ($pendaftaransByPelayanan[$pelayananName] as $item)
                                    <tr class="border-b">
                                        <td class="border px-2 py-1 text-center">{{ $counter++ }}</td>
                                        <td class="border px-2 py-1 text-center">{{ $item->noreg }}</td>
                                        <td class="border px-2 py-1 text-center">{{ $item->pasien->no_rm ?? '-' }}</td>
                                        <td class="border px-2 py-1">{{ $item->pasien->nama_pasien ?? '-' }}</td>
                                        <td class="border px-2 py-1 text-center">{{ $item->bidan->kd_bidan ?? '-' }}</td>
                                        <td class="border px-2 py-1">{{ $item->bidan->nama_bidan ?? '-' }}</td>
                                        <td class="border px-2 py-1 text-center">
                                            {{ \Carbon\Carbon::parse($item->tgl_daftar)->format('d-m-Y') }}</td>
                                        <td class="border px-2 py-1 text-center">
                                            {{ \Carbon\Carbon::parse($item->jam_daftar)->format('H:i') }}</td>
                                        <td class="border px-2 py-1 text-center">{{ $item->pelayanan->kodpel ?? '-' }}</td>
                                        <td class="border px-2 py-1">{{ $item->pelayanan->nama_pelayanan ?? '-' }}</td>
                                        <td class="border px-2 py-1 text-center">{{ $item->jenis_kunjungan }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        <tr class="font-semibold">
                            <td colspan="14" class="border px-2 py-2 text-xs text-right text-gray-700">
                                Total Seluruh Pendaftaran: {{ $all->count() }} Data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="text-right text-xs text-gray-600 mt-6">
                Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
            </div>
        </div>
    </div>
@endsection

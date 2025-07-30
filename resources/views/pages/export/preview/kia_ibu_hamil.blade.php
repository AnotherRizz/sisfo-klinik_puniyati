@extends('layouts.app')

@section('title', 'Laporan Data Pemeriksaan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex justify-between mb-3">
            <a href="{{ route('laporan.index') }}"
                class="bg-none flex gap-2 items-center border border-slate-300 hover:bg-slate-300 text-slate-700 text-sm px-4 py-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>

                Kembali ke Pemeriksaan
            </a>
            <form action="{{ route('laporan.pemeriksaan') }}" method="GET" target="_blank">
                <input type="hidden" name="filter_type" value="{{ request('filter_type') }}">
                <input type="hidden" name="jenis_pelayanan" value="{{ request('jenis_pelayanan') }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                <input type="hidden" name="bulan" value="{{ request('bulan') }}">

                <button type="submit"
                    class="bg-red-500 cursor-pointer hover:bg-red-600 text-white text-sm px-4 py-2 rounded">
                    Unduh PDF
                </button>
            </form>

        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            {{-- Header Logo dan Info --}}
            <div class="flex items-start gap-4 mb-4 relative">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                    class="w-20 h-20 absolute -top-5 left-4 object-contain">
                <div class="text-center flex-1">
                    <h1 class="text-lg font-bold uppercase">Bidan Praktik Mandiri Puniyati A.Md Keb</h1>
                    <p class="text-sm">Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
                    <p class="text-sm">Dusun Kalipejang RT01/RW07, Desa Demakan, Kec. Mojolaban, Kab. Sukoharjo</p>
                </div>
            </div>

            <div class="border-t-2 border-black my-2"></div>

            <div class="flex justify-start items-center mb-4">
                <h2 class="text-base font-semibold uppercase">Laporan Data Pemeriksaan {{ $jenisPelayanan }}
                    {{ $judul }}</h2>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs text-left border border-gray-300 whitespace-normal break-words">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-2 py-1 text-center">NO</th>
                            <th class="border px-2 py-1 text-center">TGL REG</th>
                            <th class="border px-2 py-1 text-center">NO. Periksa</th>
                            <th class="border px-2 py-1 text-center">NO. RM</th>
                            <th class="border px-2 py-1 text-center">NAMA PASIEN</th>
                            <th class="border px-2 py-1 text-center">NAMA KK</th>
                            <th class="border px-2 py-1 text-center">KELUHAN</th>
                            <th class="border px-2 py-1 text-center">UMUR KEHAMILAN</th>
                            <th class="border px-2 py-1 text-center">TD (mmHg) </th>
                            <th class="border px-2 py-1 text-center">BB (Kg) </th>
                            <th class="border px-2 py-1 text-center">TB (Cm) </th>
                            <th class="border px-2 py-1 text-center">GPA</th>
                            <th class="border px-2 py-1 text-center">LAB </th>
                            <th class="border px-2 py-1 text-center">JENIS RESTI</th>
                            <th class="border px-2 py-1 text-center">RIW TT </th>
                            <th class="border px-2 py-1 text-center">HPHT </th>
                            <th class="border px-2 py-1 text-center">HPL </th>
                            <th class="border px-2 py-1 text-center">DIAGNOSIS </th>
                            <th class="border px-2 py-1 text-center">TINDAKAN </th>
                            <th class="border px-2 py-1 text-center">OBAT </th>
                            <th class="border px-2 py-1 text-center">TGL KEMBALI </th>
                            <th class="border px-2 py-1 text-center">TINDAK LANJUT </th>
                            <th class="border px-2 py-1 text-center">NO. HP </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemeriksaans as $index => $item)
                            @php
                                $obats = $item->obatPemeriksaan ?? collect();

                                $suplemenList = $obats
                                    ->where('vitamin_suplemen', 'ya')
                                    ->map(
                                        fn($o) => '(' .
                                            ($o->jumlah_obat ?? '-') .
                                            ') ' .
                                            ($o->obat->nama_obat ?? '-') .
                                            ' (' .
                                            ($o->dosis_carkai ?? '-') .
                                            ')',
                                    )
                                    ->join(', ');

                                $obatBiasaList = $obats
                                    ->where('vitamin_suplemen', 'tidak')
                                    ->map(
                                        fn($o) => '(' .
                                            ($o->jumlah_obat ?? '-') .
                                            ') ' .
                                            ($o->obat->nama_obat ?? '-') .
                                            ' (' .
                                            ($o->dosis_carkai ?? '-') .
                                            ')',
                                    )
                                    ->join(', ');
                            @endphp

                            <tr class="border-b">
                                <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1 text-center">
                                    {{ optional($item->pendaftaran)->tgl_daftar ? \Carbon\Carbon::parse($item->pendaftaran->tgl_daftar)->translatedFormat('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-2 py-1 text-center">{{ $item->nomor_periksa ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center">{{ $item->pendaftaran->pasien->no_rm ?? '-' }}
                                </td>
                                 <td class="border px-2 py-1">{{ $item->pendaftaran->pasien->status.'. '.  $item->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                                   <td class="border px-2 py-1 text-center">{{ $item->pendaftaran->pasien->nama_kk ?? '-' }}
                                </td>
                                <td class="border px-2 py-1">{{ $item->keluhan ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->umr_hamil ? $item->umr_hamil . ' Minggu' : '-' }}
                                </td>
                                <td class="border px-2 py-1">{{ $item->td ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->bb ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->tb ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->gpa ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->lab ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->resti ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->riwayat_TT ?? '-' }}</td>
                                <td class="border px-2 py-1">
                                    {{ $item->hpht ? \Carbon\Carbon::parse($item->hpht)->translatedFormat('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $item->hpl ? \Carbon\Carbon::parse($item->hpl)->translatedFormat('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-2 py-1">{{ $item->diagnosa ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->tindakan ?? '-' }}</td>

                                {{-- Obat dan Suplemen --}}
                                <td class="border px-2 py-1 text-sm">
                                    @if ($suplemenList)
                                        <div><strong>Suplemen:</strong> {{ $suplemenList }}</div>
                                    @endif
                                    @if ($obatBiasaList)
                                        <div><strong>Obat:</strong> {{ $obatBiasaList }}</div>
                                    @endif
                                    @if (!$suplemenList && !$obatBiasaList)
                                        <div>-</div>
                                    @endif
                                </td>

                                <td class="border px-2 py-1 text-center">
                                    {{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->translatedFormat('d/m/Y') : '-' }}
                                </td>
                                <td class="border px-2 py-1">{{ $item->tindak_lnjt ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->pendaftaran->pasien->no_tlp ?? '-' }}</td>
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

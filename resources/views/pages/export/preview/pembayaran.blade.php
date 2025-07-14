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
            <form action="{{ route('laporan.pembayaran') }}" method="GET" target="_blank" id="form-export">
                <input type="hidden" name="filter_type" value="{{ $request->filter_type }}">
                <input type="hidden" name="tanggal" value="{{ $request->tanggal }}">
                <input type="hidden" name="tanggal_awal" value="{{ $request->tanggal_awal }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $request->tanggal_akhir }}">
                <input type="hidden" name="bulan" value="{{ $request->bulan }}">

                <button type="submit" class="bg-red-500 hover:bg-red-600 cursor-pointer text-white text-sm px-4 py-2 rounded">
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



            <h1 colspan="14" class="text-sm font-semibold uppercase py-2 text-slate-600">
                Laporan Data Pembayaran {{ $judul }}
            </h1>


            <div class="overflow-x-auto">
                {{-- Satu thead saja --}}
                <table class="min-w-full text-sm text-left border border-gray-300 mb-8">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border px-2 py-1 text-xs text-center">NO</th>
                            <th class="border px-2 py-1 text-xs text-center">KODE TRANSAKSI</th>
                            <th class="border px-2 py-1 text-xs text-center">NO PERIKSA</th>
                            <th class="border px-2 py-1 text-xs text-center">NO RM</th>
                            <th class="border px-2 py-1 text-xs text-center">Nama Pasien</th>
                            <th class="border px-2 py-1 text-xs text-center">Jenis Pelayanan</th>
                            <th class="border px-2 py-1 text-xs text-center">Obat</th>
                            <th class="border px-2 py-1 text-xs text-center">TINDAKAN</th>
                            <th class="border px-2 py-1 text-xs text-center">BIAYA TINDAKAN</th>
                            <th class="border px-2 py-1 text-xs text-center">BIAYA ADMINISTRASI</th>
                            <th class="border px-2 py-1 text-xs text-center">BIAYA KONSULTASI</th>
                            <th class="border px-2 py-1 text-xs text-center">BIAYA OBAT</th>
                            <th class="border px-2 py-1 text-xs text-center">TANGGAL BAYAR</th>
                            <th class="border px-2 py-1 text-xs text-center">JENIS BAYAR</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $allPembayaran = collect($pembayaransByPelayanan)->flatten(1);
                        @endphp

                        @php $counter = 1; @endphp
                        @foreach ($pembayaransByPelayanan as $namaPelayanan => $pembayarans)
                            @foreach ($pembayarans as $item)
                                @php
                                    $periksa = $item->pemeriksaanable;
                                    $pendaftaran = optional($periksa)->pendaftaran;
                                    $pasien = optional($pendaftaran)->pasien;
                                    $pelayanan = optional($pendaftaran)->pelayanan;
                                    $obatList = optional($periksa)->obat ?? collect();
                                    $totalObat = $obatList->sum('harga_jual');
                                @endphp
                                <tr class="border-b">
                                    <td class="border px-2 py-1 text-xs text-center">{{ $counter++ }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $item->kd_bayar ?? '-' }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $periksa->nomor_periksa ?? '-' }}
                                    </td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $pasien->no_rm ?? '-' }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $pasien->status.'. '. $pasien->nama_pasien ?? '-' }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        {{ $pelayanan->nama_pelayanan ?? '-' }}
                                    </td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        {{ $obatList->pluck('nama_obat')->join(', ') ?: '-' }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $item->tindakan ?? '-' }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        Rp{{ number_format($item->biaya_tindakan ?? 0, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        Rp{{ number_format($item->biaya_administrasi ?? 0, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        Rp{{ number_format($item->biaya_konsultasi ?? 0, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        Rp{{ number_format($totalObat, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1 text-xs text-center">
                                        {{ \Carbon\Carbon::parse($item->tgl_bayar ?? $item->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="border px-2 py-1 text-xs text-center">{{ $item->jenis_bayar ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="font-semibold">
                            <td colspan="14" class="border px-2 py-2 text-xs text-right text-gray-700">
                                Total Seluruh Pembayaran: {{ $allPembayaran->count() }} transaksi
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

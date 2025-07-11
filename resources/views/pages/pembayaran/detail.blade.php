@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        <a href="{{ route('pembayaran.index') }}"
            class="inline-block bg-gray-200 mb-3 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow-sm">
            ← Kembali ke daftar
        </a>

        <div class="bg-white shadow-md rounded-xl p-6">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mr-4">
                <div class="text-center">
                    <h1 class="text-lg font-bold">BIDAN PRAKTIK MANDIRI PUNIYATI A.Md Keb</h1>
                    <p>Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
                    <p>Dusun Kalipejang RT01/RW 07, Desa Demakan, Kecamatan Mojolaban, Kabupaten Sukoharjo</p>
                </div>
            </div>

            <hr class="border-t-2 border-gray-800 my-4">

            <h2 class="text-center text-xl font-semibold mb-4">BUKTI PEMBAYARAN</h2>

            {{-- Informasi Umum --}}
            <table class="w-full text-sm mb-4">
                <tr>
                    <td>No. RM</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->pasien->no_rm ?? '-' }}</td>
                    <td>No. Registrasi</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->noreg ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama Pasien</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                    <td>Tanggal Pemeriksaan</td>
                    <td>:
                        {{ \Carbon\Carbon::parse($pembayaran->pemeriksaanable->pendaftaran->pasien->tgl_lahir)->format('d-m-Y') }}
                    </td>
                </tr>

                <tr>
                    <td>Alamat</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->pasien->alamat ?? '-' }}</td>
                    <td>Tanggal Cetak</td>
                    <td>: {{ now()->format('d-m-Y') }}</td>
                </tr>

                <tr>
                    <td>Tanggal Lahir</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->pasien->tgl_lahir?->format('d-m-Y') ?? '-' }}

                    </td>
                    <td>Nama Bidan</td>
                    <td>: {{ $pembayaran->pemeriksaanable->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
                </tr>
            </table>

            {{-- Informasi Pembayaran --}}
            <table class="w-full border-collapse border text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">DESKRIPSI</th>
                        <th class="border px-4 py-2">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">ADMINISTRASI</td>
                        <td class="border px-4 py-2">Rp
                            {{ number_format($pembayaran->biaya_administrasi ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">TINDAKAN DAN LAYANAN MEDIS</td>
                        <td class="border px-4 py-2"></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">{{ $pembayaran->tindakan ?? '-' }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($pembayaran->biaya_tindakan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @foreach ($pemeriksaan->obatPemeriksaan as $item)
                        @php
                            $jumlah = $item->jumlah_obat ?? 0;
                            $subtotal = $jumlah * ($item->obat->harga_jual ?? 0);
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $item->obat->nama_obat ?? '-' }} x
                                {{ $jumlah }} (jumlah obat)
                            </td>
                            <td class="border px-4 py-2">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach



                    <tr>
                        <td class="border px-4 py-2">KONSULTASI</td>
                        <td class="border px-4 py-2">Rp
                            {{ number_format($pembayaran->biaya_konsultasi ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-gray-100 font-bold">
                        <td class="border px-4 py-2">TOTAL</td>
                        <td class="border px-4 py-2">
                            @php
                                $totalObat = $pemeriksaan->obatPemeriksaan->sum(function ($item) {
                                    return ($item->jumlah_obat ?? 0) * ($item->obat->harga_jual ?? 0);
                                });

                                $total =
                                    ($pembayaran->biaya_administrasi ?? 0) +
                                    ($pembayaran->biaya_tindakan ?? 0) +
                                    ($pembayaran->biaya_konsultasi ?? 0) +
                                    $totalObat;
                            @endphp




                            Rp {{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Tombol Cetak --}}
            <div class="mt-6 text-center">
                <a href="{{ route('pmb.bukti-bayar', $pembayaran->id) }}" target="_blank"
                    class="px-3 py-1 text-white flex gap-2 items-center justify-center text-center bg-teal-500 rounded text-sm hover:bg-teal-600">
                    Cetak Bukti
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection

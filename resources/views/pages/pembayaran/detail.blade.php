@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-xl p-6">
            <h1 class="text-2xl font-bold text-blue-700 mb-6">Detail Pembayaran</h1>

            {{-- Informasi Umum --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="Kode Bayar" :value="$pembayaran->kd_bayar" />
                <x-detail-card label="No Registrasi" :value="$pembayaran->pemeriksaan->pendaftaran->noreg ?? '-'" />
                <x-detail-card label="No Rekam Medis" :value="$pembayaran->pemeriksaan->pendaftaran->pasien->no_rm ?? '-'" />
                <x-detail-card label="Nama Pasien" :value="$pembayaran->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-'" />
                <x-detail-card label="Kode Bidan" :value="$pembayaran->pemeriksaan->pendaftaran->bidan->kd_bidan ?? '-'" />
                <x-detail-card label="Nama Bidan" :value="$pembayaran->pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-'" />

            </div>

            <h2 class="text-lg font-semibold text-gray-700 mt-8 mb-2">Obat</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <x-detail-card label="Administrasi" :value="$pembayaran->administrasi ?? '-'" />
                <x-detail-card label="Biaya Administrasi" :value="$pembayaran->biaya_administrasi
                    ? 'Rp' . number_format($pembayaran->biaya_administrasi, 0, ',', '.')
                    : '-'" />

                <x-detail-card label="Tindakan" :value="$pembayaran->tindakan ?? '-'" />
                <x-detail-card label="Biaya Tindakan" :value="$pembayaran->biaya_tindakan
                    ? 'Rp' . number_format($pembayaran->biaya_tindakan, 0, ',', '.')
                    : '-'" />

            </div>
            <h2 class="text-lg font-semibold text-gray-700 mt-8 mb-2">Pembayaran</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <x-detail-card label="Kode Obat" :value="$pembayaran->pemeriksaan->obat->kd_obat ?? '-'" />
                <x-detail-card label="Nama Obat" :value="$pembayaran->pemeriksaan->obat->nama_obat ?? '-'" />
                <x-detail-card label="Pembayaran" :value="$pembayaran->jenis_bayar ?? '-'" />
                <x-detail-card label="Tanggal Bayar" :value="$pembayaran->tgl_bayar ?? '-'" />


            </div>



            <div class="mt-6">
                <a href="{{ route('pembayaran.index') }}"
                    class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow-sm">
                    ← Kembali ke daftar
                </a>
            </div>
        </div>
    </div>
@endsection

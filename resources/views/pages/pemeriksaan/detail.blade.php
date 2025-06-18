@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-xl p-6">
            <h1 class="text-2xl font-bold text-blue-700 mb-6">Detail Pemeriksaan</h1>

            {{-- Informasi Umum --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="No Registrasi" :value="$pemeriksaan->no_periksa" />
                <x-detail-card label="No Rekam Medis" :value="$pemeriksaan->pendaftaran->pasien->no_rm ?? '-'" />
                <x-detail-card label="Nama Pasien" :value="$pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-'" />
                <x-detail-card label="Kode Bidan" :value="$pemeriksaan->pendaftaran->bidan->kd_bidan ?? '-'" />
                <x-detail-card label="Nama Bidan" :value="$pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-'" />
                <x-detail-card label="Kode Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->kodpel ?? '-'" />
                <x-detail-card label="Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->nama_pelayanan ?? '-'" />
                <x-detail-card label="Keluhan" :value="$pemeriksaan->keluhan ?? '-'" />
                <x-detail-card label="Diagnosa" :value="$pemeriksaan->diagnosa ?? '-'" />
                <x-detail-card label="Tindakan" :value="$pemeriksaan->tindakan ?? '-'" />
                <x-detail-card label="Riwayat Penyakit" :value="$pemeriksaan->riw_penyakit ?? '-'" />
                <x-detail-card label="Riwayat Imunisasi" :value="$pemeriksaan->riw_imunisasi ?? '-'" />
            </div>

            {{-- Vital Sign --}}
            <h2 class="text-lg font-semibold text-gray-700 mt-8 mb-2">Vital Sign</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <x-detail-card label="TD" :value="$pemeriksaan->td ?? '-'" />
                <x-detail-card label="BB" :value="$pemeriksaan->bb ?? '-'" />
                <x-detail-card label="TB" :value="$pemeriksaan->tb ?? '-'" />
                <x-detail-card label="Suhu" :value="$pemeriksaan->suhu ?? '-'" />
                <x-detail-card label="Saturasi Oksigen" :value="$pemeriksaan->saturasiOx ?? '-'" />
                <x-detail-card label="LILA" :value="$pemeriksaan->lila ?? '-'" />
            </div>

            {{-- Keterangan Lain --}}
            <div class="mt-8 space-y-4 text-sm">
                <div class="space-y-4 mb-10 w-full p-3 border border-gray-500 rounded-md">

                    <h1 class="text-lg font-semibold text-gray-700">Detail obat</h1>
                    @foreach ($pemeriksaan->obat as $o)
                        <div class="flex gap-7 mb-2 ">
                            <x-detail-section label="Kode Obat:" :value="$o->kd_obat" />
                            <x-detail-section label="Nama Obat dan Dosis:" :value="$o->nama_obat . ' - ' . $o->pivot->dosis_carkai" />
    
                        </div>
                    @endforeach
                </div>

                <x-detail-section label="Tanggal Kembali" :value="$pemeriksaan->tgl_kembali" />
                <x-detail-section label="Pemeriksaan Ibu Hamil" :value="$pemeriksaan->pemeriksaan_ibu_hamil" />
                <x-detail-section label="Pemeriksaan Ibu Nifas / KB" :value="$pemeriksaan->pemeriksaan_ibu_nifas_kb" />
            </div>

            <div class="mt-6">
                <a href="{{ route('pemeriksaan.index') }}"
                    class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow-sm">
                    ← Kembali ke daftar
                </a>
            </div>
        </div>
    </div>
@endsection

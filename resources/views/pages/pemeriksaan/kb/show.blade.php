@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        {{-- Button Kembali --}}
        <div class="mb-6">
            <a href="{{ route('kb.index') }}"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow-sm">
                ← Kembali ke daftar
            </a>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6">
            <h1 class="text-2xl font-bold text-slate-600 mb-6">Detail Pemeriksaan</h1>

            {{-- Informasi Umum --}}
            <h2 class="text-lg font-semibold text-sky-500 mb-4">Informasi Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="No Periksa" :value="$pemeriksaan->nomor_periksa" />
                <x-detail-card label="No Rekam Medis" :value="$pemeriksaan->pendaftaran->pasien->no_rm ?? '-'" />
                <x-detail-card label="Nama Pasien" :value="$pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-'" />
                <x-detail-card label="Kode Bidan" :value="$pemeriksaan->pendaftaran->bidan->kd_bidan ?? '-'" />
                <x-detail-card label="Nama Bidan" :value="$pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-'" />
                <x-detail-card label="Kode Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->kodpel ?? '-'" />
                <x-detail-card label="Nama Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->nama_pelayanan ?? '-'" />
            </div>

            {{-- Keluhan dan Diagnosa --}}
            <h2 class="text-lg font-semibold text-purple-500 mt-8 mb-4">Keluhan dan Diagnosa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="Keluhan" :value="$pemeriksaan->keluhan ?? '-'" />
                <x-detail-card label="Riwayat Penyakit" :value="$pemeriksaan->riw_penyakit ?? '-'" />
                <x-detail-card label="Alergi" :value="$pemeriksaan->alergi ?? '-'" />
                <x-detail-card label="Hari Pertama Haid Ibu Hamil" :value="$pemeriksaan->hpht
                    ? \Carbon\Carbon::parse($pemeriksaan->hpht)->translatedFormat('d F Y')
                    : '-'" />
                <x-detail-card label="Jumlah Anak Hidup" :value="$pemeriksaan->jmlhanak ?? '-'" />
                <x-detail-card label="Tanggal Pasang Fundus" :value="$pemeriksaan->tglpasang
                    ? \Carbon\Carbon::parse($pemeriksaan->tglpasang)->translatedFormat('d F Y')
                    : '-'" />
                <x-detail-card label="Metode KB" :value="$pemeriksaan->metode_kb ?? '-'" />
                <x-detail-card label="Edukasi" :value="$pemeriksaan->edukasi ?? '-'" />
                <x-detail-card label="Intervensi" :value="$pemeriksaan->intervensi ?? '-'" />
            </div>

            {{-- Vital Sign --}}
            <h2 class="text-lg font-semibold text-red-500 mt-8 mb-4">Vital Sign</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                 <x-detail-card label="Tensi Darah" :value="($pemeriksaan->td ? $pemeriksaan->td . ' mmHg' : '-')" />
                 <x-detail-card label="Berat Badan" :value="($pemeriksaan->td ? $pemeriksaan->td . ' Kg' : '-')" />
              
            </div>

            {{-- Obat --}}
            <h2 class="text-lg font-semibold text-teal-500 mt-8 mb-4">Detail Obat</h2>
            <div class="space-y-4 mb-6">
              @forelse ($pemeriksaan->obatPemeriksaan as $o)
                      <div class="flex gap-7">
                        {{-- Kode Obat --}}
                        <x-detail-section label="Kode Obat" :value="$o->obat->kd_obat ?? 'Tidak ada kode'" />

                        {{-- Nama Obat dan Dosis --}}
                        <x-detail-section label="Nama Obat dan Dosis" :value="$o->obat->nama_obat . ' - ' . $o->dosis_carkai" />
                    </div>
                @empty
                    <h1 class="text-sm text-slate-500">Tidak ada obat</h1>
                @endforelse
            </div>

            {{-- Tindakan dan Tindak Lanjut --}}
            <h2 class="text-lg font-semibold text-amber-500 mt-8 mb-4">Tindakan dan Tindak Lanjut</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <x-detail-card label="Tindak Lanjut" :value="($pemeriksaan->tindak_lnjt === 'Tidak Dirujuk' || $pemeriksaan->tindak_lnjt === 'Rujuk Spesialis Obsgyn')  ? ($pemeriksaan->tindak_lnjt ?? '-')  : ('Rujukan ' . ($pemeriksaan->tindak_lnjt ?? '-'))" />
                <x-detail-card label="Tanggal Kembali" :value="$pemeriksaan->tgl_kembali
                    ? \Carbon\Carbon::parse($pemeriksaan->tgl_kembali)->translatedFormat('d F Y')
                    : '-'" />

            </div>
        </div>
    </div>
@endsection

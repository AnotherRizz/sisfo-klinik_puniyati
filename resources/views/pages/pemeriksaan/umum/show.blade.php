@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        {{-- Button Kembali --}}
        <div class="mb-6">
            <a href="{{ route('umum.index') }}"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow-sm">
                ← Kembali ke daftar
            </a>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6">
            <h1 class="text-2xl font-bold text-slate-600 mb-6">Detail Pemeriksaan</h1>

            {{-- Informasi Umum --}}
            <h2 class="text-lg font-semibold text-sky-500 mb-4 flex gap-2 items-center"> <svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                </svg>
                Informasi Umum
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="No Pemeriksaan" :value="$pemeriksaan->nomor_periksa" />
                <x-detail-card label="No Rekam Medis" :value="$pemeriksaan->pendaftaran->pasien->no_rm ?? '-'" />
                <x-detail-card label="Nama Pasien" :value="$pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-'" />
                <x-detail-card label="Kode Bidan" :value="$pemeriksaan->pendaftaran->bidan->kd_bidan ?? '-'" />
                <x-detail-card label="Nama Bidan" :value="$pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-'" />
                <x-detail-card label="Kode Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->kodpel ?? '-'" />
                <x-detail-card label="Nama Pelayanan" :value="$pemeriksaan->pendaftaran->pelayanan->nama_pelayanan ?? '-'" />
            </div>

            {{-- Keluhan dan Diagnosa --}}
            <h2 class="text-lg font-semibold text-purple-500 flex gap-2 items-center mt-8 mb-4"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Keluhan dan Diagnosa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <x-detail-card label="Keluhan" :value="$pemeriksaan->keluhan ?? '-'" />
                <x-detail-card label="Diagnosa" :value="$pemeriksaan->diagnosa ?? '-'" />
                <x-detail-card label="Riwayat Penyakit" :value="$pemeriksaan->riw_penyakit ?? '-'" />
            </div>

            {{-- Vital Sign --}}
            <h2 class="text-lg font-semibold text-red-500 flex gap-2 items-center mt-8 mb-4"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                </svg>
                Vital Sign</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
        <x-detail-card label="Tensi Darah" :value="($pemeriksaan->td ? $pemeriksaan->td . ' mmHg' : '-')" />
        <x-detail-card label="Berat Badan (BB)" :value="($pemeriksaan->bb ? $pemeriksaan->bb . ' Kg' : '-')" />
        <x-detail-card label="Tinggi Badan (TB)" :value="($pemeriksaan->tb ? $pemeriksaan->tb . ' cm' : '-')" />
        <x-detail-card label="Suhu" :value="($pemeriksaan->suhu ? $pemeriksaan->suhu . ' °C' : '-')" />
        <x-detail-card label="Saturasi Oksigen" :value="($pemeriksaan->saturasiOx ? $pemeriksaan->saturasiOx . ' %' : '-')" />
    </div>

            {{-- Obat --}}
            <h2 class="text-lg font-semibold text-teal-500 flex gap-2 items-center mt-8 mb-4"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                Detail Obat</h2>
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
            <h2 class="text-lg font-semibold text-amber-500 flex gap-2 items-center mt-8 mb-4"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                </svg>
                Tindakan dan Tindak Lanjut</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <x-detail-card label="Tindakan" :value="$pemeriksaan->tindakan ?? '-'" />
                <x-detail-card label="Tindak Lanjut" :value="($pemeriksaan->tindak_lnjt === 'Tidak Dirujuk' || $pemeriksaan->tindak_lnjt === 'Rujuk Dokter Spesialis')  ? ($pemeriksaan->tindak_lnjt ?? '-')  : ('Rujukan ' . ($pemeriksaan->tindak_lnjt ?? '-'))" />

                <x-detail-card label="Tanggal Kembali" :value="$pemeriksaan->tgl_kembali
                    ? \Carbon\Carbon::parse($pemeriksaan->tgl_kembali)->translatedFormat('d F Y')
                    : '-'" />
            </div>


        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Create Pendaftaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pendaftaran</h1>
    <div class="p-7 bg-white rounded shadow">
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST">
            @csrf
            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- Pasien --}}
                <div>
                   <x-select2 id="pasien_id" name="pasien_id" label="Pasien"
    :options="$pasiens->mapWithKeys(fn($p) => [$p->id => $p->nama_pasien .' - '. $p->no_rm])"
    :selected="old('pasien_id', $selectedPasien ?? '')" />

                </div>

                {{-- Bidan --}}
                <div>
                    <x-select2 id="bidan_id" name="bidan_id" label="Bidan"
                        :options="$bidans->mapWithKeys(fn($b) => [$b->id => $b->nama_bidan])"
                        :selected="old('bidan_id')" />
                </div>

                {{-- Pelayanan --}}
                <div>
                    <x-select2 id="pelayanan_id" name="pelayanan_id" label="Pelayanan"
                        :options="$pelayanans->mapWithKeys(fn($p) => [$p->id => $p->nama_pelayanan . ' - ' . $p->kode_pelayanan])"
                        :selected="old('pelayanan_id')" />
                </div>

                {{-- Tanggal Daftar --}}
                <div>
                    <label for="tgl_daftar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" id="tgl_daftar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_daftar', now()->toDateString()) }}" required>
                </div>

                {{-- Jam Daftar --}}
                <div>
                    <label for="jam_daftar" class="block text-sm font-medium text-gray-700 mb-1">Jam Daftar</label>
                    <input type="time" name="jam_daftar" id="jam_daftar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('jam_daftar', now()->format('H:i')) }}" required>
                </div>

                {{-- Jenis Kunjungan --}}
                <div>
                    <label for="jenis_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kunjungan</label>
                    <select id="jenis_kunjungan" name="jenis_kunjungan"
                        class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="LAMA" {{ old('jenis_kunjungan') == 'LAMA' ? 'selected' : '' }}>LAMA</option>
                        <option value="BARU" {{ old('jenis_kunjungan') == 'BARU' ? 'selected' : '' }}>BARU</option>
                    </select>
                </div>

            </div>

            <button type="submit"
                class="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Simpan
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#pasien_id, #bidan_id, #pelayanan_id').select2({
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush

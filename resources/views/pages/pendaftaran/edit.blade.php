@extends('layouts.app')

@section('title', 'Edit Pendaftaran')

@section('content')
    <div class="p-7 bg-white rounded shadow">
    <h1 class="text-xl font-semibold  mt-4">Edit Data Pendaftaran</h1>
    <p class=" mb-8 text-xs text-slate-500"><strong class="text-red-500">Perhatian!!</strong> kolom dengan (*) Tidak dapat diubah</p>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       <form action="{{ route('pendaftaran.update', $pendaftaran->id) }}" method="POST">
    @csrf
    @method('PUT')

            <div class="grid gap-6 mb-6 md:grid-cols-2">
               <div>
                    <label for="noreg" class="block text-sm font-medium text-gray-700 mb-1">No Registrasi (*)</label>
                    <input type="text" name="noreg" id="noreg"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('noreg', $pendaftaran->noreg) }}" required>
                </div>

                {{-- Pasien --}}
                 <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien (*)</label>
                    <input type="text" name="name" id="name"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('name', $pendaftaran->pasien->status .'. '. $pendaftaran->pasien->nama_pasien) }}" required>
                </div>
                <input type="hidden" name="pasien_id" value="{{ old('pasien_id', $pendaftaran->pasien_id) }}">
             
                {{-- No Rekam Medis --}} 
                <div>
                    <label for="no_rm" class="block text-sm font-medium text-gray-700 mb-1">No Rekam Medis (*)</label>
                    <input type="text" name="no_rm" id="no_rm"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('no_rm', $pendaftaran->pasien->no_rm) }}" required>
                </div>
               

                {{-- Bidan --}}
                  <div>
                    <label for="bidan" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan (*)</label>
                    <input type="text" name="bidan" id="bidan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('bidan', $pendaftaran->bidan->nama_bidan) }}" required>
                </div>
                <input type="hidden" name="bidan_id" value="{{ old('bidan_id', $pendaftaran->bidan_id) }}">

                {{-- Kode Bidan --}}
                  <div>
                    <label for="kd_bidan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bidan (*)</label>
                    <input type="text" name="kd_bidan" id="kd_bidan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('kd_bidan', $pendaftaran->bidan->kd_bidan) }}" required>
                </div>
                {{-- Pelayanan --}}
                  <div>
                    <label for="pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan (*)</label>
                    <input type="text" name="pelayanan" id="pelayanan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('pelayanan', $pendaftaran->pelayanan->nama_pelayanan) }}" required>
                </div>
                <input type="hidden" name="pelayanan_id" value="{{ old('pelayanan_id', $pendaftaran->pelayanan_id) }}">

                {{-- Kode Pelayanan --}}
                  <div>
                    <label for="kodpel" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelayanan (*)</label>
                    <input type="text" name="kodpel" id="kodpel"
                        class="w-full border-gray-300 rounded-lg shadow-sm" readonly
                        value="{{ old('kodpel', $pendaftaran->pelayanan->kodpel) }}" required>
                </div>
                

                

                {{-- Tanggal Daftar --}}
                <div>
                    <label for="tgl_daftar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" id="tgl_daftar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_daftar', $pendaftaran->tgl_daftar) }}" required>
                </div>

                {{-- Jam Daftar --}}
                <div>
                    <label for="jam_daftar" class="block text-sm font-medium text-gray-700 mb-1">Jam Daftar</label>
                    <input type="time" name="jam_daftar" id="jam_daftar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('jam_daftar', $pendaftaran->jam_daftar) }}" required>
                </div>

                {{-- Jenis Kunjungan --}}
                <div>
                    <label for="jenis_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kunjungan</label>
                    <select id="jenis_kunjungan" name="jenis_kunjungan"
                        class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="LAMA" {{ old('jenis_kunjungan', $pendaftaran->jenis_kunjungan) == 'LAMA' ? 'selected' : '' }}>LAMA</option>
                        <option value="BARU" {{ old('jenis_kunjungan', $pendaftaran->jenis_kunjungan) == 'BARU' ? 'selected' : '' }}>BARU</option>
                    </select>
                </div>

            </div>

          <div class=" flex gap-4">

       <button type="submit" class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
           Perbarui
       </button>
       <a href="{{ route('pendaftaran.index') }}">
       <button type="button" class="text-white cursor-pointer bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
           batal
       </button></a>
    </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(function () {
            $('#pasien_id, #bidan_id, #pelayanan_id').select2({
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script> --}}
@endpush

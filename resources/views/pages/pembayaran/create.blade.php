@extends('layouts.app')

@section('title', 'Create Pembayaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pembayaran</h1>
    <div class="mb-6">
        <a href="{{ route('pembayaran.index') }}"
            class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded shadow-sm">
            ← Kembali ke daftar
        </a>
    </div>
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

        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf
            <h1 class="mt-2 mb-7 text-blue-500 text-xl">Data Pemeriksaan</h1>
            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- No Pemeriksaan --}}
                <div>
                    <label for="pemeriksaanable_id" class="block text-sm font-medium text-gray-700 mb-1">No
                        Pemeriksaan</label>
                    @php
                        $selectedNomor = request('nomor_periksa');
                    @endphp

                    <select id="pemeriksaanable_id" name="pemeriksaanable_id"
                        class="select2 w-full border border-gray-300 rounded px-3 py-2" required>
                        <option value="">-- Pilih Pemeriksaan --</option>
                        @foreach ($pemeriksaans as $p)
                            <option value="{{ $p->nomor_periksa }}"
                                data-pemeriksaanable-type="{{ class_basename(get_class($p)) }}"
                                data-pasien-nama="{{ $p->pendaftaran->pasien->nama_pasien ?? '' }}"
                                data-pasien-nama="{{ $p->pendaftaran->pasien->nama_pasien ?? '' }}"
                                data-bidan-nama="{{ $p->pendaftaran->bidan->nama_bidan ?? '' }}"
                                data-pelayanan-nama="{{ $p->pendaftaran->pelayanan->nama_pelayanan ?? '' }}"
                                data-obat-nama="{{ $p->obatPemeriksaan->pluck('obat.nama_obat')->join(', ') ?? '' }}"
                                {{ $selectedNomor == $p->nomor_periksa ? 'selected' : '' }}>
                                {{ $p->nomor_periksa }} - {{ $p->pendaftaran->pasien->nama_pasien ?? 'Tidak Diketahui' }}
                            </option>
                        @endforeach
                    </select>


                </div>

                {{-- Hidden input untuk menentukan tipe polymorphic --}}
                <input type="hidden" name="pemeriksaanable_type" id="pemeriksaanable_type">

                {{-- Nama Pasien --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien</label>
                    <input type="text" id="pasien_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Nama Bidan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan</label>
                    <input type="text" id="bidan_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Nama Pelayanan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan</label>
                    <input type="text" id="nama_pelayanan"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100" readonly>
                </div>

                {{-- Obat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                    <input type="text" id="obat_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Tindakan --}}
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                    <input type="text" name="tindakan" id="tindakan" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tindakan') }}" required>
                </div>
            </div>

            <h1 class="mt-10 mb-7 text-orange-500 text-xl">Biaya & Pembayaran</h1>
            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- Administrasi --}}
                <div>
                    <label for="administrasi" class="block text-sm font-medium text-gray-700 mb-1">Administrasi</label>
                    <input type="text" name="administrasi" id="administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('administrasi') }}" required>
                </div>

                {{-- Biaya Administrasi --}}
                <div>
                    <label for="biaya_administrasi" class="block text-sm font-medium text-gray-700 mb-1">Biaya
                        Administrasi</label>
                    <input type="text" name="biaya_administrasi" id="biaya_administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('biaya_administrasi') }}"
                        required>
                </div>

                {{-- Biaya Konsultasi --}}
                <div>
                    <label for="biaya_konsultasi" class="block text-sm font-medium text-gray-700 mb-1">Biaya
                        Konsultasi</label>
                    <input type="text" name="biaya_konsultasi" id="biaya_konsultasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('biaya_konsultasi') }}" required>
                </div>

                {{-- Biaya Tindakan --}}
                <div>
                    <label for="biaya_tindakan" class="block text-sm font-medium text-gray-700 mb-1">Biaya Tindakan</label>
                    <input type="text" name="biaya_tindakan" id="biaya_tindakan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('biaya_tindakan') }}" required>
                </div>

                {{-- Tanggal Bayar --}}
                <div>
                    <label for="tgl_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <input type="date" name="tgl_bayar" id="tgl_bayar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_bayar', now()->toDateString()) }}" required>
                </div>

                {{-- Jenis Pembayaran --}}
                <div>
                    <label for="jenis_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jenis Bayar</label>
                    <select name="jenis_bayar" id="jenis_bayar" class="w-full border-gray-300 rounded-lg shadow-sm"
                        required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Tunai" {{ old('jenis_bayar') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ old('jenis_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
@push('styles')
    <style>
        .select2-dropdown.select2-dropdown-scroll {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
@endpush


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#pemeriksaanable_id').select2({
                placeholder: 'Pilih No Pemeriksaan...',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'select2-dropdown-scroll'
            });

            function updateInfo() {
                const selected = $('#pemeriksaanable_id option:selected');
                $('#pasien_nama').val(selected.data('pasien-nama') || '');
                $('#bidan_nama').val(selected.data('bidan-nama') || '');
                $('#nama_pelayanan').val(selected.data('pelayanan-nama') || '');
                $('#obat_nama').val(selected.data('obat-nama') || '');
                $('#pemeriksaanable_type').val(selected.data('pemeriksaanable-type') || '');
            }

            $('#pemeriksaanable_id').on('change select2:select', updateInfo);

            updateInfo(); // inisialisasi

            // ✅ Tambahkan ini
            const hasPreselected = $('#pemeriksaanable_id').val();
            if (hasPreselected) {
                $('#pemeriksaanable_id').trigger('change');
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const fields = ['biaya_konsultasi', 'biaya_administrasi', 'biaya_tindakan'];

            fields.forEach(id => {
                const input = document.getElementById(id);
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9]/g, '');
                    if (value) value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    this.value = value;
                });

                input.addEventListener('blur', function() {
                    this.value = this.value.replace(/\./g, '');
                });
            });
        });
    </script>
@endpush

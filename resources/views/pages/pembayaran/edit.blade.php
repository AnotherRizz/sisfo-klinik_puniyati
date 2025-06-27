@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Pembayaran</h1>
    <div class="p-7 bg-white rounded shadow">
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pembayaran.update', $pembayarans->id) }}" method="POST">
            @csrf
            @method('PUT')
            <h1 class="mt-2 mb-7 text-blue-500 text-xl">Data Pemeriksaan</h1>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                {{-- Kode Bayar --}}
                <div>
                    <label for="kd_bayar" class="block text-sm font-medium text-gray-700 mb-1">Kode Bayar</label>
                    <input type="text" id="kd_bayar" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        value="{{ old('kd_bayar', $pembayarans->kd_bayar) }}" readonly>
                </div>

                {{-- No Pemeriksaan --}}
                <div>
                    {{-- Perbaikan select pemeriksaan --}}
                    <label for="no_periksa" class="block text-sm font-medium text-gray-700 mb-1">No Pemeriksaan</label>
                    <select id="no_periksa" name="pemeriksaanable_id"
                        class="select2 w-full border border-gray-300 rounded px-3 py-2">
                        @foreach ($pemeriksaans as $p)
                            <option value="{{ $p->id }}"
                                data-pasien-nama="{{ $p->pendaftaran->pasien->nama_pasien ?? '' }}"
                                data-obat-nama="{{ $p->obatPemeriksaan->pluck('obat.nama_obat')->join(', ') ?? '' }}"
                                data-pelayanan-nama="{{ $p->pendaftaran->pelayanan->nama_pelayanan ?? '' }}"
                                data-bidan-nama="{{ $p->pendaftaran->bidan->nama_bidan ?? '' }}"
                                data-pemeriksaan-type="{{ class_basename(get_class($p)) }}"
                                {{ old('pemeriksaanable_id', $pembayarans->pemeriksaanable_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nomor_periksa }} - {{ $p->pendaftaran->pasien->nama_pasien ?? 'Tidak Diketahui' }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- Nama Pasien --}}
                <div>
                    <label for="pasien_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien</label>
                    <input type="text" id="pasien_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Nama Bidan --}}
                <div>
                    <label for="bidan_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan</label>
                    <input type="text" id="bidan_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Nama Obat --}}
                <div>
                    <label for="obat_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                    <input type="text" id="obat_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>

                {{-- Nama Pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan</label>
                    <input type="text" id="nama_pelayanan"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100" readonly>
                </div>
            </div>

            <h1 class="mt-10 mb-7 text-orange-500 text-xl">Pembayaran</h1>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                {{-- Administrasi --}}
                <div>
                    <label for="administrasi" class="block text-sm font-medium text-gray-700 mb-1">Administrasi</label>
                    <input type="text" name="administrasi" id="administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('administrasi', $pembayarans->administrasi) }}" required>
                </div>
                {{-- Tambahkan ini di dalam <form> --}}
                <input type="hidden" name="pemeriksaanable_type" id="pemeriksaanable_type"
                    value="{{ old('pemeriksaanable_type', class_basename($pembayarans->pemeriksaanable_type)) }}">
                <input type="hidden" name="nomor_periksa" id="nomor_periksa"
                    value="{{ old('nomor_periksa', class_basename($pembayarans->nomor_periksa)) }}">

                {{-- Biaya Administrasi --}}
                <div>
                    <label for="biaya_administrasi" class="block text-sm font-medium text-gray-700 mb-1">Biaya
                        Administrasi</label>
                    <input type="text" name="biaya_administrasi" id="biaya_administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('biaya_administrasi', $pembayarans->biaya_administrasi) }}" required>
                </div>
                {{-- Biaya Konsultasi --}}
                <div>
                    <label for="biaya_konsultasi" class="block text-sm font-medium text-gray-700 mb-1">Biaya
                        Konsultasi</label>
                    <input type="text" name="biaya_konsultasi" id="biaya_konsultasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('biaya_konsultasi', $pembayarans->biaya_konsultasi) }}" required>
                </div>
                {{-- Tindakan --}}
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                    <input type="text" name="tindakan" id="tindakan" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tindakan', $pembayarans->tindakan) }}" required>
                </div>
                {{-- Biaya Tindakan --}}
                <div>
                    <label for="biaya_tindakan" class="block text-sm font-medium text-gray-700 mb-1">Biaya Tindakan</label>
                    <input type="number" name="biaya_tindakan" id="biaya_tindakan"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('biaya_tindakan', $pembayarans->biaya_tindakan) }}" required>
                </div>
                {{-- Tanggal Bayar --}}
                <div>
                    <label for="tgl_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <input type="date" name="tgl_bayar" id="tgl_bayar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_bayar', $pembayarans->tgl_bayar) }}" required>
                </div>
                {{-- Jenis Bayar --}}
                <div>
                    <label for="jenis_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jenis Bayar</label>
                    <select id="jenis_bayar" name="jenis_bayar" class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Tunai"
                            {{ old('jenis_bayar', $pembayarans->jenis_bayar) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer"
                            {{ old('jenis_bayar', $pembayarans->jenis_bayar) == 'Transfer' ? 'selected' : '' }}>Transfer
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">Perbarui</button>
                <a href="{{ route('pembayaran.index') }}">
                    <button type="button"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">Batal</button>
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const noPeriksaSelect = $('#no_periksa');
            const pasienNama = $('#pasien_nama');
            const bidanNama = $('#bidan_nama');
            const obatNama = $('#obat_nama');
            const pelayananNama = $('#nama_pelayanan');
            const pemeriksaanTypeInput = $('#pemeriksaanable_type');

            function updateInfo() {
                const selected = noPeriksaSelect.find(':selected');
                pasienNama.val(selected.data('pasien-nama') || '');
                bidanNama.val(selected.data('bidan-nama') || '');
                obatNama.val(selected.data('obat-nama') || '');
                pelayananNama.val(selected.data('pelayanan-nama') || '');
                pemeriksaanTypeInput.val(selected.data('pemeriksaan-type') || '');
            }

            noPeriksaSelect.on('change', updateInfo);
            updateInfo();
        });
    </script>
@endpush

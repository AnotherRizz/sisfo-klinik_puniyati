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

            <div class="grid gap-6 mb-6 md:grid-cols-2">
                {{-- Kode Bayar --}}
                <div>
                    <label for="kd_bayar" class="block text-sm font-medium text-gray-700 mb-1">Kode Bayar<span
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" id="kd_bayar" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        value="{{ old('kd_bayar', $pembayarans->kd_bayar) }}" readonly>
                </div>

                {{-- No Registrasi --}}
               <div>
   <x-select2 id="no_periksa" name="pemeriksaan_id" label="No Pemeriksaan" 
    :options="$pemeriksaans->mapWithKeys(
        fn($p) => [
            $p->id => [
                'label' => $p->no_periksa . ' - ' . ($p->pendaftaran->pasien->nama_pasien ?? 'Tidak Diketahui'),
                'data-pasien-nama' => $p->pendaftaran->pasien->nama_pasien ?? '',
                'data-obat-nama' => $p->obat ? $p->obat->pluck('nama_obat')->join(', ') : '',
                'data-pelayanan-nama' => $p->pendaftaran->pelayanan->nama_pelayanan ?? '-',
                'data-bidan-nama' => $p->pendaftaran->bidan->nama_bidan ?? '',
            ],
        ]
    )"
    :selected="$pembayarans->pemeriksaan_id"
/>

</div>



                {{-- Nama Pasien --}}
                <div>
                    <label for="pasien_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar No Pemeriksaan)</span></label>
                    <input type="text" id="pasien_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        value="{{ $pembayarans->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '' }}" readonly>
                </div>


                {{-- Nama Bidan --}}
                <div>
                    <label for="bidan_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar No Pemeriksaan)</span></label>
                    <input type="text" id="bidan_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        readonly>
                </div>



                {{-- Nama Obat --}}
                <div>
                    <label for="obat_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Obat <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar No Pemeriksaan)</span></label>
                    <input type="text" id="obat_nama" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100"
                        value="{{ $pembayarans->pemeriksaan->obat->pluck('nama_obat')->join(', ') ?? '' }}" readonly>
                </div>

                {{-- Nama pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar No Pemeriksaan)</span></label>
                    <input type="text" id="nama_pelayanan"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100" readonly>
                </div>



                {{-- administrasi --}}
                <div>
                    <label for="administrasi" class="block text-sm font-medium text-gray-700 mb-1">Administrasi</label>
                    <input type="text" name="administrasi" id="administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('administrasi', $pembayarans->administrasi) }}" required>
                </div>
                {{-- biaya administrasi --}}
                <div>
                    <label for="biaya_administrasi" class="block text-sm font-medium text-gray-700 mb-1">Biaya
                        Administrasi</label>
                    <input type="text" name="biaya_administrasi" id="biaya_administrasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('biaya_administrasi', $pembayarans->biaya_administrasi) }}" required>
                </div>
                {{-- tindakan --}}
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                    <input type="text" name="tindakan" id="tindakan" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tindakan', $pembayarans->tindakan) }}" required>
                </div>
                <div>
                    <label for="biaya_tindakan" class="block text-sm font-medium text-gray-700 mb-1">Biaya Tindakan</label>
                    <input type="number" name="biaya_tindakan" id="biaya_tindakan"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('biaya_tindakan', $pembayarans->biaya_tindakan) }}" required>
                </div>
                {{-- Tanggal Daftar --}}
                <div>
                    <label for="tgl_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <input type="date" name="tgl_bayar" id="tgl_bayar"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_bayar', $pembayarans->tgl_bayar) }}" required>
                </div>


                {{-- Jenis Kunjungan --}}
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

            <div class=" flex gap-4">

                <button type="submit"
                    class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Perbarui
                </button>
                <a href="{{ route('pembayaran.index') }}">
                    <button type="button"
                        class="text-white cursor-pointer bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        batal
                    </button></a>
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

    // Fungsi untuk memperbarui informasi berdasarkan pilihan
 function updateInfo() {
    const selected = noPeriksaSelect.find(':selected');
    console.log(selected.data());
    pasienNama.val(selected.data('pasien-nama') || '');
    bidanNama.val(selected.data('bidan-nama') || '');
    obatNama.val(selected.data('obat-nama') || '');
    pelayananNama.val(selected.data('pelayanan-nama') || '');
}

    // Event listener untuk perubahan pada Select2
    noPeriksaSelect.on('change', updateInfo);

    // Set value awal saat halaman dimuat
    updateInfo();
});

    </script>
@endpush

@extends('layouts.app')

@section('title', 'Edit Pemeriksaan')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Pemeriksaan</h1>
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

        <form action="{{ route('pemeriksaan.update', $pemeriksaan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- No reg --}}
                <div>
                    <label for="no_periksa" class="block text-sm font-medium text-gray-700 mb-1">No Periksa</label>
                    <input type="text" name="no_periksa" id="no_periksa"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('no_periksa', $pemeriksaan->no_periksa) }}" required readonly>
                </div>
                {{-- No Registrasi --}}
                <div>
                    <x-select2 id="pendaftaran_id" name="pendaftaran_id" label="No Registrasi" :options="$pendaftarans->mapWithKeys(
                        fn($p) => [
                            $p->id => [
                                'label' => $p->noreg . ' - ' . $p->pasien->nama_pasien,
                                'data-pendaftaran-id' => $p->id,
                                'data-pasien-nama' => $p->pasien->nama_pasien,
                                'data-bidan-nama' => $p->bidan->nama_bidan,
                                'data-bidan-kd' => $p->bidan->kd_bidan,
                                'data-pelayanan-nama' => $p->pelayanan->nama_pelayanan,
                                'data-pelayanan-kode' => $p->pelayanan->kodpel,
                            ],
                        ],
                    )"
                        :selected="old('pendaftaran_id', $pemeriksaan->pendaftaran_id)" />
                </div>

                {{-- Nama Pasien --}}
                <div>
                    <label for="nama_pasien" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien<span
                            class="text-xs text-gray-500">(otomatis terisi berdasar Noreg )</span></label>
                    <input type="text" name="nama_pasien" id="pasien_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>

                {{-- Bidan --}}
                <div>
                    <label for="bidan_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar Noreg )</span></label>
                    <input type="text" name="bidan_id" id="bidan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                {{-- Kode Bidan --}}
                <div>
                    <label for="kd_bidan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bidan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar Noreg )</span></label>
                    <input type="text" name="kd_bidan" id="bidan_kd" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required readonly>
                </div>


                {{-- Pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar Noreg )</span></label>
                    <input type="text" name="nama_pelayanan" id="pelayanan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                <div>
                    <label for="kodpel" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelayanan <span
                            class="text-xs text-gray-500">(otomatis terisi berdasar Noreg )</span></label>
                    <input type="text" name="kodpel" id="pelayanan_kode"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>




                <div>
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                    <input type="text" name="keluhan" id="keluhan" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('keluhan', $pemeriksaan->keluhan) }}" required>
                </div>
                <div>
                    <label for="riw_penyakit" class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit</label>
                    <input type="text" name="riw_penyakit" id="riw_penyakit"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('riw_penyakit', $pemeriksaan->riw_penyakit) }}" required>
                </div>
                <div>
                    <label for="riw_imunisasi" class="block text-sm font-medium text-gray-700 mb-1">Riwayat
                        Imunisasi</label>
                    <input type="text" name="riw_imunisasi" id="riw_imunisasi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('riw_imunisasi', $pemeriksaan->riw_imunisasi) }}" required>
                </div>
                <div>
                    <label for="td" class="block text-sm font-medium text-gray-700 mb-1">Tensi Darah <span
                            class="text-red-500 text-xs">(mmHg)</span> </label>
                    <input type="text" name="td" id="td" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('td', $pemeriksaan->td) }}" required>
                </div>
                <div>
                    <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span
                            class="text-red-500 text-xs">(Kg)</span></label>
                    <input type="number" name="bb" id="bb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('bb', $pemeriksaan->bb) }}" required>
                </div>
                <div>
                    <label for="tb" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan <span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="number" name="tb" id="tb"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('tb', $pemeriksaan->tb) }}"
                        required>
                </div>
                <div>
                    <label for="suhu" class="block text-sm font-medium text-gray-700 mb-1">Suhu<span
                            class="text-red-500 text-xs"> (°C)</span> </label>
                    <input type="number" name="suhu" id="suhu"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('suhu', $pemeriksaan->suhu) }}"
                        required>
                </div>
                <div>
                    <label for="saturasiOx" class="block text-sm font-medium text-gray-700 mb-1">Saturasi Oksigen <span
                            class="text-red-500 text-xs"> (%)</span></label>
                    <input type="number" name="saturasiOx" id="saturasiOx"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('saturasiOx', $pemeriksaan->saturasiOx) }}" required>
                </div>
                <div>
                    <label for="lila" class="block text-sm font-medium text-gray-700 mb-1">Lingkar lengan Atas <span
                            class="text-red-500 text-xs"> (Cm)</span></label>
                    <input type="number" name="lila" id="lila"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('lila', $pemeriksaan->lila) }}"
                        required>
                </div>
                <div>
                    <label for="pemeriksaan_ibu_hamil" class="block text-sm font-medium text-gray-700 mb-1">Pemeriksaan
                        Ibu
                        Hamil</label>
                    <input type="text" name="pemeriksaan_ibu_hamil" id="pemeriksaan_ibu_hamil"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('pemeriksaan_ibu_hamil', $pemeriksaan->pemeriksaan_ibu_hamil) }}">
                </div>
                <div>
                    <label for="pemeriksaan_ibu_nifas_kb" class="block text-sm font-medium text-gray-700 mb-1">Pemeriksaan
                        Ibu Nifas/KB</label>
                    <input type="text" name="pemeriksaan_ibu_nifas_kb" id="pemeriksaan_ibu_nifas_kb"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('pemeriksaan_ibu_nifas_kb', $pemeriksaan->pemeriksaan_ibu_nifas_kb) }}">
                </div>
                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-1">Diagnosa</label>
                    <input type="text" name="diagnosa" id="diagnosa"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('diagnosa', $pemeriksaan->diagnosa) }}">
                </div>
                <div>
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                    <input type="text" name="tindakan" id="tindakan"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tindakan', $pemeriksaan->tindakan) }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obat dan Dosis</label>
                    <div id="obat-wrapper">
                        @forelse ($pemeriksaan->obat as $pivotObat)
                            <div class="flex gap-2 mb-2">
                                <select name="obat_id[]" class="w-1/2 border-gray-300 rounded-lg shadow-sm" required>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}"
                                            {{ $obat->id == $pivotObat->id ? 'selected' : '' }}>
                                            {{ $obat->nama_obat }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="dosis_carkai[]"
                                    value="{{ $pivotObat->pivot->dosis_carkai }}"
                                    class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Dosis" required />
                            </div>
                        @empty
                            {{-- Jika belum ada data obat --}}
                            <div class="flex gap-2 mb-2">
                                <select name="obat_id[]" class="w-1/2 border-gray-300 rounded-lg shadow-sm" required>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="dosis_carkai[]"
                                    class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Dosis" required />
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-obat" class="text-sm text-blue-600">+ Tambah Obat</button>
                </div>
                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tgl_kembali', $pemeriksaan->tgl_kembali) }}" required>
                </div>





            </div>

            <div class=" flex gap-4">

                <button type="submit"
                    class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Perbarui
                </button>
                <a href="{{ route('pemeriksaan.index') }}">
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
        document.getElementById('add-obat').addEventListener('click', function() {
            const wrapper = document.getElementById('obat-wrapper');
            const firstRow = wrapper.children[0];
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(el => el.value = '');
            wrapper.appendChild(newRow);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#pendaftaran_id,').select2({
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <script>
        const noregSelect = $('#pendaftaran_id');
        const pasienNama = $('#pasien_nama');
        const pasienId = $('#pasien_id');
        const bidanNama = $('#bidan_nama');
        const kdBidan = $('#bidan_kd');
        const pelayananNama = $('#pelayanan_nama');
        const pelayananKode = $('#pelayanan_kode');

        function updatePasienInfo() {
            const selected = noregSelect.find(':selected');
            pasienNama.val(selected.data('pasien-nama') || '');
            pasienId.val(selected.data('pasien-id') || '');
            bidanNama.val(selected.data('bidan-nama') || '');
            kdBidan.val(selected.data('bidan-kd') || '');
            pelayananNama.val(selected.data('pelayanan-nama') || '');
            pelayananKode.val(selected.data('pelayanan-kode') || '');
        }

        $(document).ready(function() {
            noregSelect.on('change', updatePasienInfo);
            updatePasienInfo(); // untuk set awal saat reload jika ada old value
        });
    </script>
@endpush

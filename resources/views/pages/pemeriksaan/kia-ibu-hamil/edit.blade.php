@extends('layouts.app')

@section('title', 'Edit Pemeriksaan Kia')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Pemeriksaan Kia</h1>
    <div class="mb-5 flex justify-end">

        <a href="{{ route('kia-ibu-hamil.index') }}"
            class="text-white cursor-pointer flex items-center gap-2 bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-4 py-1.5 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
            </svg>
            Kembali
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

        <form action="{{ route('kia-ibu-hamil.update', $pemeriksaan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- No reg --}}
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
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" name="nama_pasien" id="pasien_nama"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required readonly>
                </div>

                {{-- Bidan --}}
                <div>
                    <label for="bidan_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan <span
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" name="bidan_id" id="bidan_nama"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required readonly>
                </div>
                {{-- Kode Bidan --}}
                <div>
                    <label for="kd_bidan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bidan <span
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" name="kd_bidan" id="bidan_kd"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required readonly>
                </div>


                {{-- Pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan <span
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" name="nama_pelayanan" id="pelayanan_nama"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required readonly>
                </div>
                <div>
                    <label for="kodpel" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelayanan <span
                            class="text-xs text-gray-500"></span></label>
                    <input type="text" name="kodpel" id="pelayanan_kode"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required readonly>
                </div>

                <div>
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                    <input type="text" name="keluhan" class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('keluhan', $pemeriksaan->keluhan) }}"
                        required>
                </div>
                <div>
                    <label for="riw_penyakit" class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit</label>
                    <input type="text" name="riw_penyakit" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('riw_penyakit', $pemeriksaan->riw_penyakit) }}" required>
                </div>

                <div>
                    <label for="td" class="block text-sm font-medium text-gray-700 mb-1">Tensi Darah <span
                            class="text-red-500 text-xs"> (mmHg)</span></label>
                    <input type="text" name="td" id="td" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('td', $pemeriksaan->td) }}" required>
                </div>
                <div>
                    <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span
                            class="text-red-500 text-xs"> (Kg)</span></label>
                    <input type="text" name="bb" id="bb" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('bb', $pemeriksaan->bb) }}" required>
                </div>
                <div>
                    <label for="tb" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan <span
                            class="text-red-500 text-xs"> (Cm)</span></label>
                    <input type="text" name="tb" id="tb" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tb', $pemeriksaan->tb) }}" required>
                </div>
                <div>
                    <label for="suhu" class="block text-sm font-medium text-gray-700 mb-1">Suhu<span
                            class="text-red-500 text-xs"> (°C)</span> </label>
                    <input type="text" name="suhu" id="suhu"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" value="{{ old('suhu', $pemeriksaan->suhu) }}" required>
                </div>
                <div>
                    <label for="saturasiOx" class="block text-sm font-medium text-gray-700 mb-1">Saturasi Oksigen<span
                            class="text-red-500 text-xs"> (%)</span> </label>
                    <input type="text" name="saturasiOx" id="saturasiOx"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" value="{{ old('saturasiOx', $pemeriksaan->saturasiOx) }}" required>
                </div>
                <div>
                    <label for="nadi" class="block text-sm font-medium text-gray-700 mb-1">Denyut Nadi Ibu
                        Hamil</label>
                    <input type="text" name="nadi" id="nadi"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" value="{{ old('nadi', $pemeriksaan->nadi) }}" required>
                </div>
                <div>
                    <label for="lila" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Lengan Atas<span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="text" name="lila" id="lila"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" value="{{ old('lila', $pemeriksaan->lila) }}" required>
                </div>
                <div>
                    <label for="hpht" class="block text-sm font-medium text-gray-700 mb-1">Hari Pertama Haid Ibu
                        Hamil
                    </label>
                    <input type="date" name="hpht" id="hpht"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500"
                        value="{{ old('hpht', $pemeriksaan->hpht) }}" required>
                </div>
                <div>
                    <label for="hpl" class="block text-sm font-medium text-gray-700 mb-1">Hari Perkiraan Lahir
                        Bayi
                    </label>
                    <input type="date" name="hpl" id="hpl"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500"
                        value="{{ old('hpl', $pemeriksaan->hpl) }}" required>
                </div>
                <div>
                    <label for="gpa" class="block text-sm font-medium text-gray-700 mb-1">Gravida, Paritas,
                        Abortus Ibu Hamil
                    </label>
                    <input type="text" name="gpa" id="gpa"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" value="{{ old('gpa', $pemeriksaan->gpa) }}" required>
                </div>
                <div>
                    <label for="riwayatkehamilankesehatan" class="block text-sm font-medium text-gray-700 mb-1">Riwayat
                        kehamilan dan
                        kesehatan</label>
                    <textarea name="riwayatkehamilankesehatan" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" id=""
                        cols="30" rows="2">{{ old('riwayatkehamilankesehatan', $pemeriksaan->riwayatkehamilankesehatan) }}</textarea>
                </div>
                <div>
                    <label for="umr_hamil" class="block text-sm font-medium text-gray-700 mb-1">Umur Kehamilan
                        <span class="text-red-500 text-xs">(Minggu)</span>
                    </label>
                    <input type="text" name="umr_hamil" id="umr_hamil" value="{{ old('umr_hamil', $pemeriksaan->umr_hamil) }}"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                </div>
                <div>
                    <label for="ling_perut" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Perut Ibu
                        Hamil

                    </label>
                    <input type="text" name="ling_perut" id="ling_perut" value="{{ old('ling_perut', $pemeriksaan->ling_perut) }}"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                </div>
                <div>
                    <label for="tifu" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Fundus
                        <span class="text-red-500 text-xs">(Cm)</span>
                    </label>
                    <input type="text" name="tifu" id="tifu" value="{{ old('tifu', $pemeriksaan->tifu) }}"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                </div>
                <div>
                    <label for="djj" class="block text-sm font-medium text-gray-700 mb-1">Denyut Jantung Janin
                        <span class="text-red-500 text-xs">(/menit)</span>
                    </label>
                    <input type="text" name="djj" id="djj" value="{{ old('djj', $pemeriksaan->djj) }}"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                </div>
                <div>
                    <label for="ltkjanin" class="block text-sm font-medium text-gray-700 mb-1">Letak Janin
                    </label>
                    <input type="text" name="ltkjanin" id="ltkjanin" value="{{ old('ltkjanin', $pemeriksaan->ltkjanin) }}"
                        class="w-full  border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                </div>
             <div class="mb-6">
                    <label for="ktrkuterus" class="block text-sm font-medium text-gray-700 mb-1">Keterangan kontraksi
                        uterus (bila ada)</label>
                    <select id="ktrkuterus" name="ktrkuterus" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Ada"
                            {{ old('ktrkuterus', $pemeriksaan->ktrkuterus) === 'Ada' ? 'selected' : '' }}>
                            Ada
                        </option>
                        <option value="Tidak Ada"
                            {{ old('ktrkuterus', $pemeriksaan->ktrkuterus) === 'Tidak Ada' ? 'selected' : '' }}>
                            Tidak Ada
                        </option>
                        
                    </select>
                </div>
                <div>
                    <label for="refla" class="block text-sm font-medium text-gray-700 mb-1">Hasil Reflex Patella Ibu
                        Hamil
                    </label>
                    <textarea name="refla" class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm" id="" cols="30"
                        rows="2">{{ old('umr_hamil', $pemeriksaan->umr_hamil) }}</textarea>
                </div>


                <div>
                    <label for="lab" class="block text-sm font-medium text-gray-700 mb-1">Hasil Lab</label>
                    <textarea name="lab" id="lab" class="w-full text-gray-500 border-gray-300  rounded-lg shadow-sm"
                        cols="30" rows="2">{{ old('lab', $pemeriksaan->lab) }}</textarea>
                </div>
                <div>
                    <label for="resti" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Resiko
                        Tinggi</label>
                    <textarea name="resti" id="resti" class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm"
                        cols="30" rows="2">{{ old('resti', $pemeriksaan->resti) }}</textarea>
                </div>


                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-1">Diagnosa</label>
                    <input type="text" name="diagnosa" id="diagnosa"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm"
                        value="{{ old('diagnosa', $pemeriksaan->diagnosa) }}">
                </div>
                <div>
                    <label for="intervensi" class="block text-sm font-medium text-gray-700 mb-1">Intervensi</label>
                    <input type="text" name="intervensi" id="intervensi"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm"
                        value="{{ old('intervensi', $pemeriksaan->intervensi) }}">
                </div>


                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm"
                        value="{{ old('tindakan', $pemeriksaan->tgl_kembali) }}" required>
                </div>
                <div class="mb-6">
                    <label for="tindak_lnjt" class="block text-sm font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                    <select id="tindak_lnjt" name="tindak_lnjt" class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Puskesmas"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Puskesmas' ? 'selected' : '' }}>Rujukan
                            Puskesmas
                        </option>
                        <option value="Klinik"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Klinik' ? 'selected' : '' }}>Rujukan
                            Klinik
                        </option>
                        <option value="Rumah Sakit"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Rumah Sakit' ? 'selected' : '' }}>Rujukan
                            Rumah Sakit
                        </option>
                        <option value="Tidak Dirujuk"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Tidak Dirujuk' ? 'selected' : '' }}>Tidak
                            Dirujuk
                        </option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obat dan Dosis</label>
                    <div id="obat-wrapper">
                        @forelse ($pemeriksaan->obatPemeriksaan as $pivotObat)
                            <div class="flex gap-2 mb-2">
                                <select name="obat_id[]" class="w-1/2 border-gray-300 text-gray-500 rounded-lg shadow-sm"
                                    required>
                                    <option value="">-- Pilih Obat --</option>
                                      @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}"
                                            {{ $obat->id == $pivotObat->obat_id ? 'selected' : '' }}>
                                            {{ $obat->nama_obat }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="dosis_carkai[]"
                                    value="{{ $pivotObat->dosis_carkai }}"
                                    class="w-1/2 border-gray-300 text-gray-500 rounded-lg shadow-sm" placeholder="Dosis"
                                    required />
                            </div>
                        @empty
                            {{-- Jika belum ada data obat --}}
                            <div class="flex gap-2 mb-2">
                                <select name="obat_id[]" class="w-1/2 border-gray-300 text-gray-500 rounded-lg shadow-sm"
                                    required>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="dosis_carkai[]"
                                    class="w-1/2 border-gray-300 text-gray-500 rounded-lg shadow-sm" placeholder="Dosis"
                                    required />
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-obat" class="text-sm text-blue-600">+ Tambah Obat</button>
                </div>

            </div>

            <div class="flex gap-2 justify-end">
                <button type="submit"
                    class="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Perbarui
                </button>
                <a href="{{ route('kia-ibu-hamil.index') }}"
                    class="text-white cursor-pointer bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Batal
                </a>
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

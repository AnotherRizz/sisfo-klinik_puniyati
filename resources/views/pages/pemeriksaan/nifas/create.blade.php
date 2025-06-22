@extends('layouts.app')

@section('title', 'Create Pemeriksaan Ibu Nifas')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pemeriksaan Ibu Nifas</h1>
    <div class="mb-5 flex justify-end">

        <a href="{{ route('nifas.index') }}"
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

        <form action="{{ route('nifas.store') }}" method="POST">
            @csrf
            <div class="grid gap-6 mb-8 md:grid-cols-2">

                {{-- No reg --}}
                {{-- No Registrasi --}}
                <div>
                    <x-select2 id="pendaftaran_id" name="pendaftaran_id" label="No Registrasi(pelayanan Ibu Nifas)"
                        :options="$pendaftarans->mapWithKeys(
                            fn($p) => [
                                $p->id => [
                                    'label' =>
                                        $p->noreg .
                                        ' - ' .
                                        $p->pasien->nama_pasien .
                                        (!$p->pemeriksaan ? ' 🔹(New)' : ''),
                                    'data-pendaftaran-id' => $p->id,
                                    'data-pasien-nama' => $p->pasien->nama_pasien,
                                    'data-bidan-nama' => $p->bidan->nama_bidan,
                                    'data-bidan-kd' => $p->bidan->kd_bidan,
                                    'data-pelayanan-nama' => $p->pelayanan->nama_pelayanan,
                                    'data-pelayanan-kode' => $p->pelayanan->kodpel,
                                ],
                            ],
                        )" :selected="old('pendaftaran_id')" />
                </div>

                {{-- Nama Pasien --}}
                <div>
                    <label for="nama_pasien" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien</label>
                    <input type="text" name="nama_pasien" id="pasien_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>

                {{-- Bidan --}}
                <div>
                    <label for="bidan_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan</label>
                    <input type="text" name="bidan_id" id="bidan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                {{-- Kode Bidan --}}
                <div>
                    <label for="kd_bidan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bidan</label>
                    <input type="text" name="kd_bidan" id="bidan_kd" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required readonly>
                </div>


                {{-- Pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan</label>
                    <input type="text" name="nama_pelayanan" id="pelayanan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                <div>
                    <label for="kodpel" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelayanan</label>
                    <input type="text" name="kodpel" id="pelayanan_kode"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>




                <div>
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                    <input type="text" name="keluhan" id="keluhan" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required>
                </div>
                <div>
                    <label for="riw_penyakit" class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit</label>
                    <input type="text" name="riw_penyakit" id="riw_penyakit"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
            
                <div>
                    <label for="frek_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Frekuensi
                        Kunjungan<span class="text-red-500 text-xs">(hari)</span></label>
                    <textarea name="frek_kunjungan" class="w-full border-gray-300 rounded-lg shadow-sm text-xs"
                        placeholder="Frekuensi kunjungan (hari ke berapa nifas) satuan hari" id="" cols="30" rows="2"></textarea>
                </div>

                <div>
                    <label for="td" class="block text-sm font-medium text-gray-700 mb-1">Tensi Darah <span
                            class="text-red-500 text-xs">(mmHg)</span> </label>
                    <input type="text" name="td" id="td" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required>
                </div>
                <div>
                    <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span
                            class="text-red-500 text-xs">(Kg)</span></label>
                    <input type="number" name="bb" id="bb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required>
                </div>
                <div>
                    <label for="tb" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan <span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="number" name="tb" id="tb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required>
                </div>
                <div>
                    <label for="suhu" class="block text-sm font-medium text-gray-700 mb-1">Suhu<span
                            class="text-red-500 text-xs"> (°C)</span> </label>
                    <input type="number" name="suhu" id="suhu" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required>
                </div>
                <div>
                    <label for="saturasiOx" class="block text-sm font-medium text-gray-700 mb-1">Saturasi Oksigen <span
                            class="text-red-500 text-xs"> (%)</span></label>
                    <input type="number" name="saturasiOx" id="saturasiOx"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
                <div>
                    <label for="riw_alergi" class="block text-sm font-medium text-gray-700 mb-1">Alergi </label>
                    <input type="text" name="riw_alergi" id="riw_alergi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
                <div>
                    <label for="tifu" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Fundus <span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="number" name="tifu" id="tifu"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-gray-500" value="" required>
                </div>
                <div>
                    <label for="lochea" class="block text-sm font-medium text-gray-700 mb-1">Lochea</span></label>
                    <textarea name="lochea" class="w-full border-gray-300 rounded-lg  text-xs  shadow-sm" id="" cols="30"
                        rows="2" placeholder="Jumlah cairan yang keluar, warna cairan, bau cairan"></textarea>
                </div>
                <div>
                    <label for="payudara" class="block text-sm font-medium text-gray-700 mb-1">Payudara</span></label>
                    <textarea name="payudara" class="w-full border-gray-300 rounded-lg text-xs shadow-sm" id="" cols="30"
                        rows="2"
                        placeholder="Keterangan tentang payudara apakah mengalami pembengkakan, puting lecet, asi keluar atau tidak)"></textarea>
                </div>
                <div>
                    <label for="luka_jahit" class="block text-sm font-medium text-gray-700 mb-1">Luka Jahit</span></label>
                    <textarea name="luka_jahit" class="w-full border-gray-300  text-xs  rounded-lg shadow-sm" id=""
                        cols="30" rows="2" placeholder="Keterangan kondisi luka jahit apabila ada"></textarea>
                </div>
                <div>
                    <label for="tgl_lahir" class="block text-sm font-medium text-gray-500 mb-1">Tanggal
                        Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required>
                </div>
                <div>
                    <label for="tmpt_persalinan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Persalinan
                        </span></label>
                    <input type="text" name="tmpt_persalinan" id="tmpt_persalinan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
                <div class="mb-6">
                    <label for="bantu_persalinan" class="block text-sm font-medium text-gray-700 mb-1">Penolong
                        Persalinan</label>
                    <select id="bantu_persalinan" name="bantu_persalinan"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Bidan" {{ old('bantu_persalinan') == 'Bidan' ? 'selected' : '' }}>Bidan</option>
                        <option value="Dokter" {{ old('bantu_persalinan') == 'Dokter' ? 'selected' : '' }}>Dokter
                        </option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="jns_persalinan" class="block text-sm font-medium text-gray-700 mb-1">Jenis
                        Persalinan</label>
                    <select id="jns_persalinan" name="jns_persalinan"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Spontan" {{ old('jns_persalinan') == 'Spontan' ? 'selected' : '' }}>Spontan</option>
                        <option value="Cesar" {{ old('jns_persalinan') == 'Cesar' ? 'selected' : '' }}>Cesar</option>
                        <option value="Vakum" {{ old('jns_persalinan') == 'Vakum' ? 'selected' : '' }}>Vakum
                        </option>
                    </select>
                </div>


                <div>
                    <label for="besar_rahim" class="block text-sm font-medium text-gray-700 mb-1">Besar Rahim</label>
                    <input type="text" name="besar_rahim" id="besar_rahim"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="">
                </div>
                <div>
                    <label for="infeksi_kompli" class="block text-sm font-medium text-gray-700 mb-1">Infeksi /
                        Komplikasi</span></label>
                    <textarea name="infeksi_kompli" class="w-full border-gray-300 rounded-lg  text-xs  shadow-sm" id=""
                        cols="30" rows="2" placeholder="Keterangan infeksi dan komplikasi apabila ada"></textarea>
                </div>
                <div>
                    <label for="edukasi" class="block text-sm font-medium text-gray-700 mb-1">Edukasi</span></label>
                    <textarea name="edukasi" class="w-full border-gray-300 rounded-lg text-xs  shadow-sm" id="" cols="30"
                        rows="2" placeholder="Edukasi kepada pasien"></textarea>
                </div>



                <div>
                    <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-1">Diagnosa</label>
                    <input type="text" name="diagnosa" id="diagnosa"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="">
                </div>
                <div>
                    <label for="intervensi" class="block text-sm font-medium text-gray-700 mb-1">Intervensi</label>
                    <input type="text" name="intervensi" id="intervensi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="">
                </div>


                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-gray-500"
                        value="{{ old('tgl_kembali', now()->toDateString()) }}" required>
                </div>
                <div class="mb-6">
                    <label for="tindak_lnjt" class="block text-sm font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                    <select id="tindak_lnjt" name="tindak_lnjt"
                        class="w-full text-gray-500 border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Puskesmas" {{ old('tindak_lnjt') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas
                        </option>
                        <option value="Klinik" {{ old('tindak_lnjt') == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                        <option value="Rumah Sakit" {{ old('tindak_lnjt') == 'Rumah Sakit' ? 'selected' : '' }}>Rumah
                            Sakit
                        </option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obat dan Dosis</label>
                    <div id="obat-wrapper">
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
                    </div>
                    <button type="button" id="add-obat" class="text-sm cursor-pointer text-blue-600">+ Tambah
                        Obat</button>
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
        document.getElementById('add-obat').addEventListener('click', function() {
            const wrapper = document.getElementById('obat-wrapper');
            const newRow = wrapper.children[0].cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(input => input.value = '');
            wrapper.appendChild(newRow);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%',
                escapeMarkup: function(markup) {
                    return markup; // allow HTML in options
                }
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

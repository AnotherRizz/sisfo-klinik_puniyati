@extends('layouts.app')

@section('title', 'Create Pemeriksaan Ibu Nifas')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pemeriksaan Ibu Nifas</h1>
    <div class="mb-6">
        <a href="{{ route('nifas.index') }}"
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
                                        (!$p->pemeriksaan ? ' (Baru)' : ''),
                                    'data-pendaftaran-id' => $p->id,
                                    'data-pasien-nama' => $p->pasien->nama_pasien,
                                    'data-bidan-nama' => $p->bidan->nama_bidan,
                                    'data-bidan-kd' => $p->bidan->kd_bidan,
                                    'data-pelayanan-nama' => $p->pelayanan->nama_pelayanan,
                                    'data-pelayanan-kode' => $p->pelayanan->kodpel,
                                ],
                            ],
                        )" :selected="$pendaftaran_id ?: old('pendaftaran_id')" />
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
                        value="">
                </div>
                <div>
                    <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span
                            class="text-red-500 text-xs">(Kg)</span></label>
                    <input type="text" name="bb" id="bb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="">
                </div>
                <div>
                    <label for="tb" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan <span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="text" name="tb" id="tb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="">
                </div>
                <div>
                    <label for="suhu" class="block text-sm font-medium text-gray-700 mb-1">Suhu<span
                            class="text-red-500 text-xs"> (°C)</span> </label>
                    <input type="text" name="suhu" id="suhu"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="">
                </div>
                <div>
                    <label for="saturasiOx" class="block text-sm font-medium text-gray-700 mb-1">Saturasi Oksigen <span
                            class="text-red-500 text-xs"> (%)</span></label>
                    <input type="text" name="saturasiOx" id="saturasiOx"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="">
                </div>
                <div>
                    <label for="alergi" class="block text-sm font-medium text-gray-700 mb-1">Alergi </label>
                    <input type="text" name="alergi" id="alergi"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
                <div>
                    <label for="tifu" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Fundus <span
                            class="text-red-500 text-xs">(Cm)</span></label>
                    <input type="text" name="tifu" id="tifu"
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
                    <label for="lukajahit" class="block text-sm font-medium text-gray-700 mb-1">Luka Jahit</span></label>
                    <textarea name="lukajahit" class="w-full border-gray-300  text-xs  rounded-lg shadow-sm" id=""
                        cols="30" rows="2" placeholder="Keterangan kondisi luka jahit apabila ada"></textarea>
                </div>
                <div>
                    <label for="tgllahir" class="block text-sm font-medium text-gray-500 mb-1">Tanggal
                        Lahir Bayi</label>
                    <input type="date" name="tgllahir" id="tgllahir"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm" value="" required>
                </div>
                <div>
                    <label for="tmptpersalinan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Persalinan
                        </span></label>
                    <input type="text" name="tmptpersalinan" id="tmptpersalinan"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required>
                </div>
                <div class="mb-6">
                    <label for="bantupersalinan" class="block text-sm font-medium text-gray-700 mb-1">Penolong
                        Persalinan</label>
                    <select id="bantupersalinan" name="bantupersalinan"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Bidan" {{ old('bantupersalinan') == 'Bidan' ? 'selected' : '' }}>Bidan</option>
                        <option value="Dokter" {{ old('bantupersalinan') == 'Dokter' ? 'selected' : '' }}>Dokter
                        </option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="jnspersalinan" class="block text-sm font-medium text-gray-700 mb-1">Jenis
                        Persalinan</label>
                    <select id="jnspersalinan" name="jnspersalinan"
                        class="w-full border-gray-300 text-gray-500 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Spontan" {{ old('jnspersalinan') == 'Spontan' ? 'selected' : '' }}>Spontan</option>
                        <option value="Cesar" {{ old('jnspersalinan') == 'Cesar' ? 'selected' : '' }}>Cesar</option>
                        <option value="Vacum" {{ old('jnspersalinan') == 'Vacum' ? 'selected' : '' }}>Vacum
                        </option>
                    </select>
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

                @php
                    $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" min="{{$minDate}}"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-gray-500"
                        value="{{ old('tgl_kembali') }}">
                </div>
                <div class="mb-6">
                    <label for="tindak_lnjt" class="block text-sm font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                    <select id="tindak_lnjt" name="tindak_lnjt" class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>

                        <option value="Puskesmas" {{ old('tindak_lnjt') == 'Puskesmas' ? 'selected' : '' }}>Rujukan
                            Puskesmas</option>
                        <option value="Klinik" {{ old('tindak_lnjt') == 'Klinik' ? 'selected' : '' }}>Rujukan Klinik
                        </option>
                        <option value="Rumah Sakit" {{ old('tindak_lnjt') == 'Rumah Sakit' ? 'selected' : '' }}>Rujukan
                            Rumah Sakit</option>
                        <option value="Rujuk Spesialis Obsgyn"
                            {{ old('tindak_lnjt') == 'Rujuk Spesialis Obsgyn' ? 'selected' : '' }}>
                            Rujuk Spesialis Obsgyn</option>
                        <option value="Tidak Dirujuk" {{ old('tindak_lnjt') == 'Tidak Dirujuk' ? 'selected' : '' }}>Tidak
                            Dirujuk</option>
                    </select>
                </div>
                <div class="mb-6 col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obat, Dosis dan Jumlah</label>
                    <div id="obat-wrapper">
                        <div class="obat-row flex flex-col gap-1 mb-4">
                            <div class="flex gap-2">
                                <select name="obat_id[]" class="obat-select w-1/2 border-gray-300 rounded-lg shadow-sm">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-stok="{{ $obat->stok_obat }}">
                                            {{ $obat->nama_obat }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="dosis_carkai[]"
                                    class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Dosis" />
                                <input type="text" name="jumlah_obat[]"
                                    class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Jumlah Obat" />
                            </div>
                            <p class="stok-info text-xs text-end text-gray-500"></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            function updateStok(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const stok = selectedOption.dataset.stok || '';
                const wrapper = selectElement.closest('.obat-row');
                const stokInfo = wrapper.querySelector('.stok-info');
                stokInfo.textContent = stok ? `Stok tersedia: ${stok}` : '';
            }

            // Event listener awal untuk baris pertama
            document.querySelectorAll('.obat-select').forEach(select => {
                select.addEventListener('change', function() {
                    updateStok(this);
                });
            });

            // Tambah obat dinamis
            document.getElementById('add-obat').addEventListener('click', function() {
                const wrapper = document.getElementById('obat-wrapper');
                const originalRow = wrapper.querySelector('.obat-row');
                const clone = originalRow.cloneNode(true);

                // Reset nilai input dan stok
                clone.querySelectorAll('input, select').forEach(el => el.value = '');
                clone.querySelector('.stok-info').textContent = '';

                wrapper.appendChild(clone);

                // Tambahkan event listener baru
                clone.querySelector('.obat-select').addEventListener('change', function() {
                    updateStok(this);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Elemen Select dan Input yang akan di-update
            const noregSelect = $('#pendaftaran_id');
            const pasienNama = $('#pasien_nama');
            const pasienId = $('#pasien_id');
            const bidanNama = $('#bidan_nama');
            const kdBidan = $('#bidan_kd');
            const pelayananNama = $('#pelayanan_nama');
            const pelayananKode = $('#pelayanan_kode');

            // Fungsi untuk memperbarui informasi pasien berdasarkan pilihan
            function updatePasienInfo() {
                const selected = noregSelect.find(':selected');
                const pasienNamaValue = selected.data('pasien-nama') || '';
                const pasienIdValue = selected.data('pendaftaran-id') || '';
                const bidanNamaValue = selected.data('bidan-nama') || '';
                const kdBidanValue = selected.data('bidan-kd') || '';
                const pelayananNamaValue = selected.data('pelayanan-nama') || '';
                const pelayananKodeValue = selected.data('pelayanan-kode') || '';

                pasienNama.val(pasienNamaValue);
                pasienId.val(pasienIdValue);
                bidanNama.val(bidanNamaValue);
                kdBidan.val(kdBidanValue);
                pelayananNama.val(pelayananNamaValue);
                pelayananKode.val(pelayananKodeValue);
            }

            // Inisialisasi jika ada nilai awal (contoh: old data)
            if (noregSelect.val()) {
                updatePasienInfo();
            }

            // Event listener untuk perubahan nilai select
            noregSelect.on('change', updatePasienInfo);
        });
    </script>
@endpush

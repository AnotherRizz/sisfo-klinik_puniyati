@extends('layouts.app')

@section('title', 'Edit Pemeriksaan KB')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Pemeriksaan KB</h1>
    <div class="mb-5 flex justify-end">

        <a href="{{ route('kb.index') }}"
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

        <form action="{{ route('kb.update', $pemeriksaan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-3">

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
                            class="text-xs text-gray-600"></span></label>
                    <input type="text" name="nama_pasien" id="pasien_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>

                {{-- Bidan --}}
                <div>
                    <label for="bidan_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Bidan <span
                            class="text-xs text-gray-600"></span></label>
                    <input type="text" name="bidan_id" id="bidan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                {{-- Kode Bidan --}}
                <div>
                    <label for="kd_bidan" class="block text-sm font-medium text-gray-700 mb-1">Kode Bidan <span
                            class="text-xs text-gray-600"></span></label>
                    <input type="text" name="kd_bidan" id="bidan_kd" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="" required readonly>
                </div>


                {{-- Pelayanan --}}
                <div>
                    <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelayanan <span
                            class="text-xs text-gray-600"></span></label>
                    <input type="text" name="nama_pelayanan" id="pelayanan_nama"
                        class="w-full border-gray-300 rounded-lg shadow-sm" value="" required readonly>
                </div>
                <div>
                    <label for="kodpel" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelayanan <span
                            class="text-xs text-gray-600"></span></label>
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
                    <label for="alergi" class="block text-sm font-medium text-gray-700 mb-1">Riwayat
                        Alergi</label>
                    <input type="text" name="alergi" id="alergi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('alergi', $pemeriksaan->alergi) }}" required>
                </div>
                <div>
                    <label for="td" class="block text-sm font-medium text-gray-700 mb-1">Tensi Darah <span
                            class="text-red-500 text-xs">(mmHg)</span> </label>
                    <input type="text" name="td" id="td" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('td', $pemeriksaan->td) }}" >
                </div>
                  <div>
                    <label for="bb" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span
                            class="text-red-500 text-xs">(Kg)</span></label>
                    <input type="text" name="bb" id="bb" class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('bb', $pemeriksaan->bb) }}" >
                </div>
              
              
                <div>
                    <label for="hpht" class="block text-sm font-medium text-gray-700 mb-1">Hari Pertama Haid</label>
                    <input type="date" name="hpht" id="hpht"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-gray-600"
                        value="{{ old('hpht', $pemeriksaan->hpht) }}" required>
                </div>
                <div>
                    <label for="jmlhanak" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anak</label>
                    <input type="text" name="jmlhanak" id="jmlhanak"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('jmlhanak', $pemeriksaan->jmlhanak) }}">
                </div>
                <div>
                    <label for="tglpasang" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pasang</label>
                    <input type="date" name="tglpasang" id="tglpasang"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-gray-600"
                        value="{{ old('tglpasang', $pemeriksaan->tglpasang) }}" required>
                </div>
                <div class="mb-6">
                    <label for="metode_kb" class="block text-sm font-medium text-gray-700 mb-1">Metode KB</label>
                    <select id="metode_kb" name="metode_kb"
                        class="w-full border-gray-300 text-gray-600 rounded-lg shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Pil" {{ old('metode_kb', $pemeriksaan->metode_kb) == 'Pil' ? 'selected' : '' }}>
                            Pil</option>
                        <option value="Suntik"
                            {{ old('metode_kb', $pemeriksaan->metode_kb) == 'Suntik' ? 'selected' : '' }}>Suntik</option>
                        <option value="Implan"
                            {{ old('metode_kb', $pemeriksaan->metode_kb) == 'Implan' ? 'selected' : '' }}>Implan</option>
                        <option value="IUD" {{ old('metode_kb', $pemeriksaan->metode_kb) == 'IUD' ? 'selected' : '' }}>
                            IUD</option>
                        <option value="Kondom"
                            {{ old('metode_kb', $pemeriksaan->metode_kb) == 'Kondom' ? 'selected' : '' }}>Kondom</option>
                      
                    </select>
                </div>
                <div>
                    <label for="edukasi" class="block text-sm font-medium text-gray-700 mb-1">Edukasi</span></label>
                    <textarea name="edukasi" class="w-full border-gray-300 rounded-lg text-xs  shadow-sm" id="" cols="30"
                        rows="2" placeholder="Edukasi kepada pasien">{{ old('edukasi', $pemeriksaan->edukasi) }}</textarea>
                </div>
                <div>
                    <label for="intervensi" class="block text-sm font-medium text-gray-700 mb-1">Intervensi</label>
                    <input type="text" name="intervensi" id="intervensi"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('intervensi', $pemeriksaan->intervensi) }}">
                </div>
              
                 @php
                    $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp

                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Kembali</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" min="{{$minDate}}"
                        class="w-full border-gray-300 rounded-lg shadow-sm"
                        value="{{ old('tindakan', $pemeriksaan->tgl_kembali) }}" >
                </div>
                <div class="mb-6">
                    <label for="tindak_lnjt" class="block text-sm font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                    <select id="tindak_lnjt" name="tindak_lnjt" class="w-full border-gray-300 rounded-lg shadow-sm">
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
                        <option value="Rujuk Spesialis Obsgyn"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Rujuk Spesialis Obsgyn' ? 'selected' : '' }}> 
                            Rujuk Spesialis Obsgyn
                        </option>
                        <option value="Tidak Dirujuk"
                            {{ old('tindak_lnjt', $pemeriksaan->tindak_lnjt) === 'Tidak Dirujuk' ? 'selected' : '' }}>Tidak Dirujuk
                        </option>
                    </select>
                </div>

              <div class="mb-6 col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obat, Dosis dan Jumlah</label>
                    <div id="obat-wrapper">
                        @forelse ($pemeriksaan->obatPemeriksaan as $pivotObat)
                            <div class="obat-row flex flex-col gap-1 mb-4">
                                <div class="flex gap-2">
                                    <select name="obat_id[]"
                                        class="obat-select w-1/2 border-gray-300 rounded-lg shadow-sm" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}" data-stok="{{ $obat->stok_obat }}"
                                                {{ $obat->id == $pivotObat->obat_id ? 'selected' : '' }}>
                                                {{ $obat->nama_obat }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="dosis_carkai[]" value="{{ $pivotObat->dosis_carkai }}"
                                        class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Dosis" required />

                                    <input type="text" name="jumlah_obat[]" value="{{ $pivotObat->jumlah_obat }}"
                                        class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Jumlah Obat"
                                        required />
                                </div>
                                <p class="stok-info text-xs text-end text-gray-600">
                                    {{-- Optional: tampilkan stok jika obat dipilih --}}
                                    @php
                                        $stokDipilih = $obats->firstWhere('id', $pivotObat->obat_id)?->stok_obat;
                                    @endphp
                                    {{ $stokDipilih ? 'Stok tersedia: ' . $stokDipilih : '' }}
                                </p>
                            </div>
                        @empty
                            <div class="obat-row flex flex-col gap-1 mb-4">
                                <div class="flex gap-2">
                                    <select name="obat_id[]"
                                        class="obat-select w-1/2 border-gray-300 rounded-lg shadow-sm" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}" data-stok="{{ $obat->stok_obat }}">
                                                {{ $obat->nama_obat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="dosis_carkai[]"
                                        class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Dosis" required />
                                    <input type="text" name="jumlah_obat[]"
                                        class="w-1/2 border-gray-300 rounded-lg shadow-sm" placeholder="Jumlah Obat"
                                        required />
                                </div>
                                <p class="stok-info text-xs text-end text-gray-600"></p>
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
                <a href="{{ route('kb.index') }}"
                    class="text-white cursor-pointer bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Batal
               </a>
            </div>


        </form>
    </div>
@endsection

@push('scripts')
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-obat');
            const wrapper = document.getElementById('obat-wrapper');

            addBtn.addEventListener('click', function() {
                const firstRow = wrapper.querySelector('.obat-row');
                const newRow = firstRow.cloneNode(true);

                // Kosongkan semua input dan select
                newRow.querySelectorAll('input, select').forEach(el => {
                    el.value = '';
                });

                // Kosongkan teks stok juga
                const stokInfo = newRow.querySelector('.stok-info');
                if (stokInfo) stokInfo.textContent = '';

                wrapper.appendChild(newRow);
            });

            function updateStok(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const stok = selectedOption.dataset.stok || '';
                const wrapper = selectElement.closest('.obat-row');
                const stokInfo = wrapper.querySelector('.stok-info');
                stokInfo.textContent = stok ? `Stok tersedia: ${stok}` : '';
            }

            document.querySelectorAll('.obat-select').forEach(select => {
                select.addEventListener('change', function() {
                    updateStok(this);
                });
            });
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

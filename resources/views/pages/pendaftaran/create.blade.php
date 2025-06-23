@extends('layouts.app')

@section('title', 'Create Pendaftaran')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pendaftaran</h1>
    <div class="flex justify-end">

        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
            class="px-3 py-1.5 text-white flex items-center gap-2 mt-2 cursor-pointer bg-green-500 hover:bg-green-600  rounded mb-4 text-sm">
            Daftar Pasien Baru
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>

        </button>
    </div>
    <!-- Main modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600 ">
                    <h3 class="text-lg font-semibold text-gray-900 ">
                        Tambah Pasien Baru
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('pasien.store') }}" class="p-3" method="POST">
                    @csrf
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <input type="hidden" name="source_form" value="pendaftaran">
                        <div>
                            <label for="nik_pasien" class="block mb-2 text-sm font-medium text-gray-900">NIK Pasien</label>
                            <input type="number" id="nik_pasien" name="nik_pasien" value="{{ old('nik_pasien') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                placeholder="cth : 3674567893567835" required />
                        </div>
                        <div>
                            <label for="nama_pasien" class="block mb-2 text-sm font-medium text-gray-900">Nama
                                Pasien</label>
                            <input type="text" id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                placeholder="cth : Risqi" required />
                        </div>
                        <div>
                            <label for="tempt_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tempat
                                Lahir</label>
                            <input type="text" id="tempt_lahir" name="tempt_lahir" value="{{ old('tempt_lahir') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                placeholder="cth : Boyolali" required />
                        </div>
                        <div>
                            <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal
                                Lahir</label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required />
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                                Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>

                            </select>
                        </div>
                        <div>
                            <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat</label>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required />
                        </div>
                        <div>
                            <label for="agama" class="block mb-2 text-sm font-medium text-gray-900">Agama</label>
                            <select id="agama" name="agama"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="islam">Islam</option>
                                <option value="kristen">Kristen</option>
                                <option value="katholik">Katholik</option>
                                <option value="hindu">Hindu</option>
                                <option value="budha">Budha</option>
                                <option value="aliran kepercayaan">Aliran Kepercayaan</option>
                            </select>
                        </div>
                        <div>
                            <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900">Pendidikan</label>
                            <select id="pendidikan" name="pendidikan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="belum sekolah">Belum Sekolah</option>
                                <option value="SD">SD</option>
                                <option value="SMP/SLTP">SMP/SLTP</option>
                                <option value="SMA/SLTA">SMA/SLTA</option>
                                <option value="Diploma I/II/III">Diploma I/II/III</option>
                                <option value="S1/S2/S3">S1/S2/S3</option>
                                <option value="lain-lain">Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label for="pekerjaan" class="block mb-2 text-sm font-medium text-gray-900">Pekerjaan</label>
                            <select id="pekerjaan" name="pekerjaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="Wiraswasta">Wiraswasta</option>
                                <option value="PNS">PNS</option>
                                <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                <option value="Pelajar">Pelajar</option>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Petani">Petani</option>
                                <option value="Pedagang">Pedagang</option>
                                <option value="Tidak Bekerja">Tidak Bekerja</option>
                            </select>
                        </div>
                        <div>
                            <label for="penanggungjawab" class="block mb-2 text-sm font-medium text-gray-900">Penanggung
                                Jawab</label>
                            <input type="text" id="penanggungjawab" name="penanggungjawab"
                                value="{{ old('penanggungjawab') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required />
                        </div>
                        <div>
                            <label for="golda" class="block mb-2 text-sm font-medium text-gray-900">Golongan
                                Darah</label>
                            <select id="golda" name="golda"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div>
                            <label for="no_tlp" class="block mb-2 text-sm font-medium text-gray-900">No Telepon</label>
                            <input type="number" id="no_tlp" name="no_tlp" value="{{ old('no_tlp') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required />
                        </div>
                    </div>
                    <div class=" flex justify-end">
                        <button type="submit"
                            class="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

        <form action="{{ route('pendaftaran.store') }}" method="POST">
            @csrf
            <div class="grid gap-6 mb-6 md:grid-cols-2">

                {{-- Pasien --}}
                <div>
                    <x-select2 id="pasien_id" name="pasien_id" label="Pasien" :options="$pasiens->mapWithKeys(fn($p) => [$p->id => $p->nama_pasien . ' - ' . $p->no_rm])" :selected="old('pasien_id', $selectedPasien ?? '')" />

                </div>

                {{-- Bidan --}}
                <div>
                    <x-select2 id="bidan_id" name="bidan_id" label="Bidan" :options="$bidans->mapWithKeys(fn($b) => [$b->id => $b->nama_bidan])" :selected="old('bidan_id')" />
                </div>

                {{-- Pelayanan --}}
                <div>
                    <x-select2 id="pelayanan_id" name="pelayanan_id" label="Pelayanan" :options="$pelayanans->mapWithKeys(
                        fn($p) => [$p->id => $p->nama_pelayanan . ' - ' . $p->kode_pelayanan],
                    )"
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
                    <label for="jenis_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Jenis
                        Kunjungan</label>
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
        $(document).ready(function() {
            $('#pasien_id, #bidan_id, #pelayanan_id').select2({
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush

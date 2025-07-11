@extends('layouts.app')

@section('title', 'Edit Pasien')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Pasien</h1>
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

        <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="no_rm" class="block mb-2 text-sm font-medium text-gray-900">No RM</label>
                    <input type="text" id="no_rm" value="{{ old('no_rm', $pasien->no_rm) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required readonly />
                    <input type="hidden" name="no_rm" value="{{ old('no_rm', $pasien->no_rm) }}">
                </div>
                <div>
                    <label for="nik_pasien" class="block mb-2 text-sm font-medium text-gray-900">NIK Pasien</label>
                    <input type="text" id="nik_pasien" name="nik_pasien" maxlength="16"
                        value="{{ old('nik_pasien', $pasien->nik_pasien) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="nama_pasien" class="block mb-2 text-sm font-medium text-gray-900">Nama Pasien</label>
                    <input type="text" id="nama_pasien" name="nama_pasien"
                        value="{{ old('nama_pasien', $pasien->nama_pasien) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select id="status" name="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        <option value="NY" {{ old('status', $pasien->status) == 'NY' ? 'selected' : '' }}>NY</option>
                        <option value="TN" {{ old('status', $pasien->status) == 'TN' ? 'selected' : '' }}>TN</option>
                        <option value="SDR" {{ old('status', $pasien->status) == 'SDR' ? 'selected' : '' }}>SDR</option>
                        <option value="NN" {{ old('status', $pasien->status) == 'NN' ? 'selected' : '' }}>NN</option>
                        <option value="AN" {{ old('status', $pasien->status) == 'AN' ? 'selected' : '' }}>AN</option>
                    </select>
                </div>

                <div>
                    <label for="tempt_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tempat Lahir</label>
                    <input type="text" id="tempt_lahir" name="tempt_lahir"
                        value="{{ old('tempt_lahir', $pasien->tempt_lahir) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>

                @php
                    $maxDate = \Carbon\Carbon::yesterday()->format('Y-m-d');
                @endphp

                <div>
                    <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir"
                        value="{{ old('tgl_lahir', isset($pasien->tgl_lahir) ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') : '') }}"
                        max="{{ $maxDate }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>

                <div>
                    <label for="umur" class="block mb-2 text-sm font-medium text-gray-900">Umur</label>
                    <input type="text" id="umur" name="umur" value="{{ old('umur', $pasien->umur) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="nama_kk" class="block mb-2 text-sm font-medium text-gray-900">Nama KK </label>
                    <input type="text" id="nama_kk" name="nama_kk" value="{{ old('nama_kk', $pasien->nama_kk) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-Laki",
                            {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki
                        </option>
                        <option value="Perempuan",
                            {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>

                    </select>
                </div>
                <div>
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $pasien->alamat) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="agama" class="block mb-2 text-sm font-medium text-gray-900">Agama</label>
                    <select id="agama" name="agama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        @foreach (['islam', 'kristen', 'katholik', 'hindu', 'budha', 'aliran kepercayaan'] as $agama)
                            <option value="{{ $agama }}"
                                {{ old('agama', $pasien->agama) == $agama ? 'selected' : '' }}>{{ ucfirst($agama) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900">Pendidikan</label>
                    @php
                        $pendidikanList = [
                            'belum sekolah',
                            'SD',
                            'SMP/SLTP',
                            'SMA/SLTA',
                            'Diploma I/II/III',
                            'S1/S2/S3',
                            'lain-lain',
                        ];
                    @endphp
                    <select id="pendidikan" name="pendidikan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        @foreach ($pendidikanList as $pendidikan)
                            <option value="{{ $pendidikan }}"
                                {{ old('pendidikan', $pasien->pendidikan) == $pendidikan ? 'selected' : '' }}>
                                {{ $pendidikan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pekerjaan" class="block mb-2 text-sm font-medium text-gray-900">Pekerjaan</label>
                    @php
                        $pekerjaanList = [
                            'Wiraswasta',
                            'PNS',
                            'Ibu Rumah Tangga',
                            'Pelajar',
                            'Mahasiswa',
                            'Petani',
                            'Pedagang',
                            'Tidak Bekerja',
                        ];
                    @endphp
                    <select id="pekerjaan" name="pekerjaan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        @foreach ($pekerjaanList as $pekerjaan)
                            <option value="{{ $pekerjaan }}"
                                {{ old('pekerjaan', $pasien->pekerjaan) == $pekerjaan ? 'selected' : '' }}>
                                {{ $pekerjaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="penanggungjawab" class="block mb-2 text-sm font-medium text-gray-900">Penanggung
                        Jawab</label>
                    <input type="text" id="penanggungjawab" name="penanggungjawab"
                        value="{{ old('penanggungjawab', $pasien->penanggungjawab) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="golda" class="block mb-2 text-sm font-medium text-gray-900">Golongan Darah</label>
                    @php
                        $goldaList = ['-', 'A', 'B', 'AB', 'O'];
                    @endphp
                    <select id="golda" name="golda"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        @foreach ($goldaList as $gol)
                            <option value="{{ $gol }}"
                                {{ old('golda', $pasien->golda) == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="no_tlp" class="block mb-2 text-sm font-medium text-gray-900">No Telepon</label>
                    <input type="text" id="no_tlp" name="no_tlp" value="{{ old('no_tlp', $pasien->no_tlp) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
            </div>
            <div class=" flex gap-4">

                <button type="submit"
                    class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Perbarui
                </button>
                <a href="{{ route('pasien.index') }}">
                    <button type="button"
                        class="text-white cursor-pointer bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        batal
                    </button></a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tglLahirInput = document.getElementById('tgl_lahir');
                const umurInput = document.getElementById('umur');

                function hitungUmur(tgl) {
                    const tglLahir = new Date(tgl);
                    const today = new Date();

                    let tahun = today.getFullYear() - tglLahir.getFullYear();
                    let bulan = today.getMonth() - tglLahir.getMonth();
                    let hari = today.getDate() - tglLahir.getDate();

                    if (hari < 0) {
                        bulan--;
                        hari += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
                    }

                    if (bulan < 0) {
                        tahun--;
                        bulan += 12;
                    }

                    if (!isNaN(tahun)) {
                        umurInput.value = `${tahun} Tahun ${bulan} Bulan ${hari} Hari`;
                    } else {
                        umurInput.value = '';
                    }
                }

                // Jalankan saat halaman dimuat jika sudah ada value
                if (tglLahirInput.value) {
                    hitungUmur(tglLahirInput.value);
                }

                tglLahirInput.addEventListener('change', function() {
                    hitungUmur(this.value);
                });
            });
        </script>
    @endpush
@endsection

@extends('layouts.app')

@section('title', 'Create Pasien')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Data Pasien</h1>
    <div class="mb-6">
        <a href="{{ route('pasien.index') }}"
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

        <form action="{{ route('pasien.store') }}" method="POST">
            @csrf
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <input type="hidden" name="source_form" value="pasien">
                <div>
                    <label for="nik_pasien" class="block mb-2 text-sm font-medium text-gray-900">NIK Pasien</label>
                    <input type="number" id="nik_pasien" name="nik_pasien" value="{{ old('nik_pasien') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="cth : 3674567893567835" required />
                </div>
                <div>
                    <label for="nama_pasien" class="block mb-2 text-sm font-medium text-gray-900">Nama Pasien</label>
                    <input type="text" id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="cth : zainuba" required />
                </div>
                <div>
                    <label for="tempt_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tempat Lahir</label>
                    <input type="text" id="tempt_lahir" name="tempt_lahir" value="{{ old('tempt_lahir') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="cth : Sukoharjo" required />
                </div>
                <div>
                    <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="umur" class="block mb-2 text-sm font-medium text-gray-900">Umur</label>
                    <input type="text" id="umur" name="umur" value="{{ old('umur') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
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
                    <label for="golda" class="block mb-2 text-sm font-medium text-gray-900">Golongan Darah</label>
                    <select id="golda" name="golda"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                        <option value="">-- Pilih --</option>
                        <option value="-">-</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>
                <div>
                    <label for="no_tlp" class="block mb-2 text-sm font-medium text-gray-900">No Telepon</label>
                    <input type="text" id="no_tlp" name="no_tlp" value="{{ old('no_tlp') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Simpan
            </button>
        </form>
    </div>


@endsection

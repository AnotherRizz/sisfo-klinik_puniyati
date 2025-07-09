@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Data Obat</h1>
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

        <form action="{{ route('obat.update', $obat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="nama_obat" class="block mb-2 text-sm font-medium text-gray-900">Nama Obat</label>
                    <input type="text" id="nama_obat" name="nama_obat"
                        value="{{ old('nama_obat', $obat->nama_obat) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="kd_obat" class="block mb-2 text-sm font-medium text-gray-900">Kode Obat</label>
                    <input type="text"  id="kd_obat" name="kd_obat"
                        value="{{ old('kd_obat', $obat->kd_obat) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>

                <div>
                    <label for="jenis_obat" class="block mb-2 text-sm font-medium text-gray-900">Jenis Obat</label>
                    <input type="text" id="jenis_obat" name="jenis_obat" value="{{ old('jenis_obat', $obat->jenis_obat) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
                <div>
                    <label for="stok_obat" class="block mb-2 text-sm font-medium text-gray-900">Stok Obat</label>
                    <input type="text" id="stok_obat" name="stok_obat" value="{{ old('stok_obat', $obat->stok_obat) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>

                <div>
                    <label for="harga_jual" class="block mb-2 text-sm font-medium text-gray-900">Harga Jual</label>
                    <input type="text" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $obat->harga_jual) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required />
                </div>
            </div>
            <div class=" flex gap-4">

                <button type="submit"
                    class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Perbarui
                </button>
                <a href="{{ route('obat.index') }}">
                    <button type="button"
                        class="text-white cursor-pointer bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        batal
                    </button></a>
            </div>
        </form>
    </div>
@endsection

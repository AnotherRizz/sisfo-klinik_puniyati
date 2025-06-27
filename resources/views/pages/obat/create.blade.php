@extends('layouts.app')

@section('title', 'Create Bidan')

@section('content')
<h1 class="text-xl font-semibold mb-4">Tambah Data Obat</h1>
<div class="mb-6">
        <a href="{{ route('obat.index') }}"
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

   <form action="{{ route('obat.store') }}" method="POST">
    @csrf
    <div class="grid gap-6 mb-6 md:grid-cols-2">
       
        <div>
            <label for="nama_obat" class="block mb-2 text-sm font-medium text-gray-900">Nama Obat</label>
            <input type="text" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="cth : Risqi" required />
        </div>
        <div>
            <label for="jenis_obat" class="block mb-2 text-sm font-medium text-gray-900">Jenis Obat</label>
            <input type="text" id="jenis_obat" name="jenis_obat" value="{{ old('jenis_obat') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="cth : Boyolali" required />
        </div>
        <div>
            <label for="stok_obat" class="block mb-2 text-sm font-medium text-gray-900">Stok</label>
            <input type="number" id="stok_obat" name="stok_obat" value="{{ old('stok_obat') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="cth : Boyolali" required />
        </div>
      
        <div>
            <label for="harga_beli" class="block mb-2 text-sm font-medium text-gray-900">Harga Beli</label>
            <input type="number" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
        </div>
        <div>
            <label for="harga_jual" class="block mb-2 text-sm font-medium text-gray-900">Harga Jual</label>
            <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
        </div>
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
        Simpan
    </button>
</form>
</div>


@endsection

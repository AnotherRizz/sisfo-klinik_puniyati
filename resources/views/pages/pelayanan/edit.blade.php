@extends('layouts.app')

@section('title', 'Edit Pelayanan')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Data Pelayanan</h1>
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

   <form action="{{ route('pelayanan.update', $pelayanan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid gap-6 mb-6 md:grid-cols-2">
       <div>
           <label for="nama_pelayanan" class="block mb-2 text-sm font-medium text-gray-900">Nama pelayanan</label>
           <input type="text" id="nama_pelayanan" name="nama_pelayanan" value="{{ old('nama_pelayanan', $pelayanan->nama_pelayanan) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
       </div>
        <div>
            <label for="kodpel" class="block mb-2 text-sm font-medium text-gray-900">Kode pelayanan</label>
            <input type="text" disabled id="kodpel" name="kodpel" value="{{ old('kodpel', $pelayanan->kodpel) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
        </div>
       
       
    </div>
    <div class=" flex gap-4">

       <button type="submit" class="text-white cursor-pointer bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
           Perbarui
       </button>
       <a href="{{ route('pelayanan.index') }}">
       <button type="button" class="text-white cursor-pointer bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
           batal
       </button></a>
    </div>
</form>
</div>
@endsection

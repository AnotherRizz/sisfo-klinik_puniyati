@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Laporan</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Laporan Pendaftaran --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.8" stroke="currentColor"
                class="size-6 w-full h-32 object-cover ">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-700">Laporan Pendaftaran</h2>
                <p class="text-sm text-gray-500">Mencetak laporan pendaftaran sesuai jenis pelayanan per bulan.</p>
                <form action="{{ route('laporan.pendaftaran.preview') }}" method="GET" class="mt-4">
                    <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm mb-2"
                        value="{{ old('bulan', date('Y-m')) }}" required>

                    <select name="jenis_pelayanan" class="border-gray-300 rounded w-full p-2 text-sm mb-4" required>
                        <option value="">Pilih Pelayanan</option>
                        @foreach ($pelayanans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelayanan }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full bg-blue-500 cursor-pointer hover:bg-blue-600 text-white py-2 rounded text-sm">Cetak
                        PDF</button>
                </form>
            </div>
        </div>

        {{-- Laporan Pemeriksaan --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.8"
                stroke="currentColor" class="size-6 w-full h-32 object-cover ">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-700">Laporan Pemeriksaan</h2>
                <p class="text-sm text-gray-500">Mencetak laporan pemeriksaan sesuai jenis pelayanan per bulan.</p>
                <form action="{{ route('laporan.pemeriksaan.preview') }}"method="GET" class="mt-4">
                    <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm mb-2"
                        value="{{ old('bulan', date('Y-m')) }}" required>

                    <select name="jenis_pelayanan" class="border-gray-300 rounded w-full p-2 text-sm mb-4" required>
                        <option value="">Pilih Pelayanan</option>
                        @foreach ($pelayanans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelayanan }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full bg-green-500 cursor-pointer hover:bg-green-600 text-white py-2 rounded text-sm">Cetak
                        PDF</button>
                </form>
            </div>
        </div>

        {{-- Laporan Pembayaran --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.8"
                stroke="currentColor" class="size-6 w-full h-32 object-cover ">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>

            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-700">Laporan Pembayaran</h2>
                <p class="text-sm text-gray-500">Mencetak laporan pembayaran sesuai jenis pelayanan per bulan.</p>
                <form action="{{ route('laporan.pembayaran.preview') }}" method="GET" class="mt-4">
                    <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm mb-2"
                        value="{{ old('bulan', date('Y-m')) }}" required>

                    <select name="jenis_pelayanan" class="border-gray-300 rounded w-full p-2 text-sm mb-4" required>
                        <option value="">Pilih Pelayanan</option>
                        @foreach ($pelayanans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelayanan }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full cursor-pointer bg-red-500 hover:bg-red-600 text-white py-2 rounded text-sm">
                        Cetak PDF
                    </button>
                </form>


            </div>
        </div>
    </div>
@endsection

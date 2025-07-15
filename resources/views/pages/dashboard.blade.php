@extends('layouts.app')

@section('title', 'Dashboard Klinik')

@section('content')
<div class="p-4">
    {{-- Welcome Message & Profile Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white flex items-center justify-between rounded shadow col-span-2">
            <div>

                <h2 class="text-xl font-semibold text-gray-800">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}</h2>
                <p class="text-sm text-gray-600 mt-1">Sistem Rekam Medis Elektronik Bidan Praktik Mandiri Puniyati Amd. Keb Mojolaban</p>
            </div>
           
            <img src="{{ asset('images/logo.png') }}" class="w-24 mt-4" alt="">
          
        </div>
        <div class="p-4 bg-blue-50 rounded shadow flex items-center">
              @role('bidan')
            <img src="{{ asset('images/pp.jpg') }}" class="w-16 h-16 rounded-full object-cover border border-blue-300 mr-4" alt="Profil">
              @endrole
            <div>
                <h3 class="font-bold text-blue-800">Puniyati Amd. Keb</h3>
                @role('bidan')
                <p class="text-sm text-gray-600">Administrator</p>
                @endrole
                @role('admin')
                <p class="text-sm text-gray-600">Bidan Praktik</p>
                @endrole
            </div>
        </div>
    </div>

    {{-- Informasi Ringkasan Klinik --}}
    <div class="mb-6">
        <div class="p-6 bg-white rounded shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Informasi Praktik Bidan</h3>
            <p class="text-gray-700 text-sm leading-relaxed">
                Bidan Praktik Mandiri Puniyati Amd. Keb terletak di Dusun Kalipelang RT01/RW 07, Desa Demakan, Kecamatan Mojolaban, Kabupaten Sukoharjo. Sejak tahun 2019 dengan SIPB: <strong class="text-blue-800">0026/SIPB/33.11/VI/2019</strong>, memberikan layanan sebagai berikut:
            </p>
            <div class="flex flex-wrap gap-2 mt-3">
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1 rounded-full">Pelayanan Umum</span>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Pelayanan Kesehatan Ibu Hamil</span>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Pelayanan Kesehatan Anak</span>
                <span class="bg-pink-100 text-pink-800 text-xs font-medium px-3 py-1 rounded-full">Pelayanan Ibu Nifas</span>
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">Pelayanan KB</span>
            </div>
            <div class="mt-4 text-sm text-gray-700">
                <p><strong>Jam Praktik:</strong></p>
                <ul class="list-disc ml-5">
                    <li>Pagi: 06.00 - 08.00</li>
                    <li>Sore: 17.00 - 19.00</li>
                </ul>
                <p class="mt-2">Rata-rata pelayanan: <strong>200 pasien per bulan</strong>.</p>
            </div>
        </div>
    </div>

    {{-- Statistik Pengguna & Pemeriksaan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="p-6 bg-white rounded shadow">
            <p class="text-sm text-gray-600">Jumlah Pasien</p>
            <h2 class="text-3xl font-bold text-blue-800">{{ $pasien }}</h2>
        </div>
        <div class="p-6 bg-white rounded shadow">
            @role('bidan')
            <p class="text-sm text-gray-600">Jumlah Data Pemeriksaan</p>
            <h2 class="text-3xl font-bold text-teal-700">{{ $pemeriksaan }}</h2>

            @endrole
            @role('admin')
            <p class="text-sm text-gray-600">Jumlah Pendaftaran Hari Ini</p>
            <h2 class="text-3xl font-bold text-teal-700">{{ $pendaftaranHariIni }}</h2>

            @endrole
        </div>
    </div>
   

    {{-- Visi Misi --}}
    <div class="mb-6">
        <div class="p-6 bg-white rounded shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Visi</h3>
            <p class="text-gray-700 text-sm mb-4">
                Menjadi tenaga kesehatan yang mampu memanfaatkan ilmu dan keterampilan secara optimal demi memberikan manfaat nyata bagi masyarakat.
            </p>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Misi</h3>
            <ol class="list-decimal pl-6 text-sm text-gray-700 space-y-1">
                <li>Mengaplikasikan ilmu kebidanan untuk meningkatkan kesehatan ibu dan anak di lingkungan sekitar.</li>
                <li>Memberikan pelayanan kesehatan yang ramah, profesional, dan mudah diakses oleh masyarakat.</li>
                <li>Terus belajar dan mengembangkan diri untuk meningkatkan kualitas pelayanan.</li>
                <li>Membangun hubungan yang harmonis dengan masyarakat demi terciptanya lingkungan yang sehat.</li>
            </ol>
        </div>
    </div>
</div>
@endsection

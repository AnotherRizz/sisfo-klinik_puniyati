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
                <form action="{{ route('laporan.pendaftaran.preview') }}" method="GET" class="mt-4"
                    id="form-pendaftaran">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan:</label>
                    <div x-data="{ filter: 'hari' }">
                        <div class="flex gap-4 text-sm mb-3">
                            <label><input type="radio" name="filter_type" value="hari" x-model="filter"> Per
                                Hari</label>
                            <label><input type="radio" name="filter_type" value="rentang" x-model="filter">
                                Rentang</label>
                            <label><input type="radio" name="filter_type" value="bulan" x-model="filter"> Per
                                Bulan</label>
                        </div>

                        {{-- Per Hari --}}
                        <div x-show="filter === 'hari'" class="mb-3" x-transition>
                            <input type="date" name="tanggal" class="border-gray-300 rounded w-full p-2 text-sm">
                        </div>

                        {{-- Rentang --}}
                        <div x-show="filter === 'rentang'" class="mb-3" x-transition>
                            <div class="flex gap-2">
                                <input type="date" name="tanggal_awal"
                                    class="border-gray-300 rounded w-full p-2 text-sm">
                                <input type="date" name="tanggal_akhir"
                                    class="border-gray-300 rounded w-full p-2 text-sm">
                            </div>
                        </div>

                        {{-- Per Bulan --}}
                        <div x-show="filter === 'bulan'" class="mb-3" x-transition>
                            <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm"
                                value="{{ old('bulan', date('Y-m')) }}">
                        </div>
                    </div>


                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm">
                        Lihat Laporan
                    </button>
                </form>


            </div>
        </div>
        @role('bidan')
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
                    <form action="{{ route('laporan.pemeriksaan.preview') }}" method="GET" class="mt-4"
                        id="form-pemeriksaan">

                        <div x-data="{ filter: 'hari' }">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan:</label>
                            <div class="flex gap-4 text-sm mb-3">
                                <label><input type="radio" name="filter_type" value="hari" x-model="filter"> Per
                                    Hari</label>
                                <label><input type="radio" name="filter_type" value="rentang" x-model="filter">
                                    Rentang</label>
                                <label><input type="radio" name="filter_type" value="bulan" x-model="filter"> Per
                                    Bulan</label>
                            </div>

                            {{-- Per Hari --}}
                            <div x-show="filter === 'hari'" class="mb-3" x-transition>
                                <input type="date" name="tanggal" class="border-gray-300 rounded w-full p-2 text-sm">
                            </div>

                            {{-- Rentang --}}
                            <div x-show="filter === 'rentang'" class="mb-3" x-transition>
                                <div class="flex gap-2">
                                    <input type="date" name="tanggal_awal"
                                        class="border-gray-300 rounded w-full p-2 text-sm">
                                    <input type="date" name="tanggal_akhir"
                                        class="border-gray-300 rounded w-full p-2 text-sm">
                                </div>
                            </div>

                            {{-- Per Bulan --}}
                            <div x-show="filter === 'bulan'" class="mb-3" x-transition>
                                <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm"
                                    value="{{ old('bulan', date('Y-m')) }}">
                            </div>
                        </div>


                        <select name="jenis_pelayanan" class="border-gray-300 rounded w-full p-2 text-sm mb-4" required>
                            <option value="">Pilih Pelayanan</option>
                            <option value="umum">Umum</option>
                            <option value="kesehatan_ibu_hamil">Kesehatan Ibu Hamil</option>
                            <option value="kesehatan_anak">Kesehatan Anak</option>
                            <option value="ibu_nifas">Ibu Nifas</option>
                            <option value="kb">KB</option>
                        </select>

                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded text-sm">Lihat Laporan</button>
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
                    <p class="text-sm text-gray-500">Mencetak laporan pembayaran semua jenis pelayanan per bulan.</p>

                    <form action="{{ route('laporan.pembayaran.preview') }}" method="GET" class="mt-4"
                        id="form-pembayaran">
                        <div x-data="{ filter: 'hari' }">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan:</label>
                            <div class="flex gap-4 text-sm mb-3">
                                <label><input type="radio" name="filter_type" value="hari" x-model="filter"> Per
                                    Hari</label>
                                <label><input type="radio" name="filter_type" value="rentang" x-model="filter">
                                    Rentang</label>
                                <label><input type="radio" name="filter_type" value="bulan" x-model="filter"> Per
                                    Bulan</label>
                            </div>

                            {{-- Per Hari --}}
                            <div x-show="filter === 'hari'" class="mb-3" x-transition>
                                <input type="date" name="tanggal" class="border-gray-300 rounded w-full p-2 text-sm">
                            </div>

                            {{-- Rentang --}}
                            <div x-show="filter === 'rentang'" class="mb-3" x-transition>
                                <div class="flex gap-2">
                                    <input type="date" name="tanggal_awal"
                                        class="border-gray-300 rounded w-full p-2 text-sm">
                                    <input type="date" name="tanggal_akhir"
                                        class="border-gray-300 rounded w-full p-2 text-sm">
                                </div>
                            </div>

                            {{-- Per Bulan --}}
                            <div x-show="filter === 'bulan'" class="mb-3" x-transition>
                                <input type="month" name="bulan" class="border-gray-300 rounded w-full p-2 text-sm"
                                    value="{{ old('bulan', date('Y-m')) }}">
                            </div>
                        </div>


                        <button type="submit"
                            class="w-full cursor-pointer bg-red-500 hover:bg-red-600 text-white py-2 rounded text-sm">
                            Lihat Laporan
                        </button>
                    </form>

                </div>

            </div>
        @endrole
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateFilterVisibilityByForm(formId, prefix) {
                const form = document.getElementById(formId);
                const radios = form.querySelectorAll('input[name="filter_type"]');
                const hari = document.getElementById(`${prefix}-hari`);
                const rentang = document.getElementById(`${prefix}-rentang`);
                const bulan = document.getElementById(`${prefix}-bulan`);

                function toggle() {
                    const selected = [...radios].find(r => r.checked)?.value;
                    hari.classList.add('hidden');
                    rentang.classList.add('hidden');
                    bulan.classList.add('hidden');

                    if (selected === 'hari') hari.classList.remove('hidden');
                    if (selected === 'rentang') rentang.classList.remove('hidden');
                    if (selected === 'bulan') bulan.classList.remove('hidden');
                }

                radios.forEach(r => r.addEventListener('change', toggle));
                toggle();
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateFilterVisibilityByForm('form-pendaftaran', 'pendaftaran');
                updateFilterVisibilityByForm('form-pembayaran', 'pembayaran');
                updateFilterVisibilityByForm('form-pemeriksaan', 'pemeriksaan');
            });
        });
    </script>

@endsection

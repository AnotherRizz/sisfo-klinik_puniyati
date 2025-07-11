@extends('layouts.app')

@section('title', 'Master Data Obat')

@section('content')
    <h1 class="text-xl font- mb-4">Data Obat</h1>

    <div class="flex w-full gap-2 items-center justify-end">
        <a href="{{ route('obat.export') }}" target="_blank"
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-red-500 hover:bg-red-600 rounded mb-4 text-sm">Cetak
            Data<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>

        <a href="{{ route('obat.create') }}"
            class="px-3 py-1.5 text-white flex gap-2 cursor-pointer bg-sky-500 hover:bg-sky-600 rounded mb-4 text-sm">
            Tambah Obat
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>



        </a>
    </div>


    <div class="bg-white rounded shadow p-2">
        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('obat.index')" name="search" placeholder="Cari nama / kode obat..." />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('obat.index')" />

        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Obat
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Obat
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Obat
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                   
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($obats as $obat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($obats->currentPage() - 1) * $obats->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $obat->kd_obat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $obat->nama_obat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $obat->jenis_obat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $obat->stok_obat }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($obat->harga_jual) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('obat.edit', $obat->id) }}"
                                class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>
                            {{-- <form id="delete-form-{{ $obat->id }}" action="{{ route('obat.destroy', $obat->id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="px-3 py-1 cursor-pointer text-white bg-red-500 rounded hover:bg-red-600"
                                    onclick="confirmDelete({{ $obat->id }})">
                                    Hapus
                                </button>
                            </form> --}}
                            <!-- Tombol modal -->
                            <button data-modal-target="tambahStokModal-{{ $obat->id }}"
                                data-modal-toggle="tambahStokModal-{{ $obat->id }}"
                                class="px-3 py-1 bg-green-600 text-white cursor-pointer rounded hover:bg-green-700">
                                Tambah Stok
                            </button>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div id="tambahStokModal-{{ $obat->id }}" tabindex="-1"
                                class="hidden fixed top-0 left-0 right-0 z-50  justify-center items-center w-full h-full bg- bg-opacity-30">
                                <div class="bg-white p-6 rounded-lg w-full max-w-md">
                                    <h3 class="text-lg font-semibold mb-4">Tambah Stok: {{ $obat->nama_obat }}</h3>
                                    <h3 class="text-sm mb-4">Stok Sekarang: {{ $obat->stok_obat }}</h3>
                                    <form action="{{ route('obat.tambahStok', $obat->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="jumlah-{{ $obat->id }}" class="block mb-1 text-sm">Jumlah stok
                                                yang ingin
                                                ditambahkan:</label>
                                            <input type="text" name="jumlah" id="jumlah-{{ $obat->id }}" required
                                                min="1" class="w-full border border-gray-300 px-3 py-2 rounded">
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" data-modal-hide="tambahStokModal-{{ $obat->id }}"
                                                class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada data obat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal Flowbite -->
        {{-- <div id="tambahStokModal-{{ $obat->id }}" tabindex="-1"
            class="hidden fixed top-0 left-0 right-0 z-50  justify-center items-center w-full h-full bg-none bg-opacity-10">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Tambah Stok: {{ $obat->nama_obat }}</h3>
                <h3 class="text-lg font-semibold mb-4">Stok Sekarang: {{ $obat->stok_obat }}</h3>
                <form action="{{ route('obat.tambahStok', $obat->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="jumlah-{{ $obat->id }}" class="block mb-1 text-sm">Masukkan jumlah stok yang ingin
                            ditambahkan.</label>
                        <input type="number" name="jumlah" id="jumlah-{{ $obat->id }}" required min="1"
                            class="w-full border border-gray-300 px-3 py-2 rounded">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" data-modal-hide="tambahStokModal-{{ $obat->id }}"
                            class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div> --}}
       <div class="mt-4">
            {{ $obats->links() }}
        </div>


    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>




@if(session('stok_rendah') && count(session('stok_rendah')) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('toast-container');
            const data = @json(session('stok_rendah'));

            data.forEach((item, index) => {
                const toast = document.createElement('div');
                toast.className = 'flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow text-red-500';
                toast.innerHTML = `
                    <div class="inline-flex items-center justify-center w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                        </svg>
                    </div>
                    <div class="ms-3 text-sm font-normal">
                        Stok <strong>${item.nama_obat}</strong> tinggal <strong>${item.stok_obat}</strong>
                        <p>Segera lakukan penambahan stok</p>
                    </div>
                  
                    <button onclick="this.parentElement.remove()" class="ms-auto text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 ">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                        </svg>
                    </button>
                `;
                container.appendChild(toast);
            });
        });
    </script>
@endif


@endsection
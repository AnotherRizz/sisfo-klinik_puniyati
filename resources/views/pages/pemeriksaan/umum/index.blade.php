@extends('layouts.app')

@section('title', 'Data Pemeriksaan')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Data Pemeriksaan Umum</h1>

    <div class="flex w-full gap-2 justify-start items-center ">
        <div>
            <h1 class="text-lg font-bold text-slate-500">
                @if (request('filter_tanggal') == 'semua')
                Menampilkan Semua Pemeriksaan
                @else
                Menampilkan Pemeriksaan Tanggal {{ now()->locale('id')->translatedFormat('d F Y') }}
                @endif
            </h1>
        </div>


    </div>
    {{-- ALPINE.JS SEARCH FILTER --}}
    <div x-data="{ search: '' }" class="bg-white rounded shadow p-2 overflow-x-auto">
        <div class="flex justify-end my-3 items-center gap-2">
            <x-filter-tanggal id="filterTanggal" name="filter_tanggal" class="border-gray-300 rounded px-7 py-2 text-sm"
                :selected="request('filter_tanggal')" />

        </div>

        <div class="w-full flex justify-between">

            <div class="mb-4 basis-1/2">
                <x-search-input :action="route('umum.index')" name="search" placeholder="Cari nama, alamat, tanggal lahir, nomor rm" />
            </div>
            <x-paginate :options="[2, 5, 10, 15, 20]" :default="10" :action="route('umum.index')" />

        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No Registrasi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No RM</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Lahir </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($paginatedData as $item)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->pendaftaran->noreg ?? $item->noreg }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pendaftaran->pasien->no_rm ?? ($item->pasien->no_rm ?? '-') }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pendaftaran->pasien->nama_pasien ?? ($item->pasien->nama_pasien ?? '-') }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            @if ($item->pendaftaran->pasien->tgl_lahir ?? $item->pasien->tgl_lahir)
                                {{ \Carbon\Carbon::parse($item->pendaftaran->pasien->tgl_lahir ?? $item->pasien->tgl_lahir)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pendaftaran->pasien->umur ?? ($item->pasien->umur ?? '-') }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $item->pendaftaran->pasien->alamat ?? ($item->pasien->alamat ?? '-') }}
                        </td>

                        <!-- Kolom Status -->
                        <td class="px-4 py-2 text-sm text-gray-900">
                            @if ($item instanceof \App\Models\Pendaftaran)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Belum Diperiksa
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Diperiksa
                                </span>
                            @endif
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="px-4 py-2 text-sm text-gray-900 flex gap-1">
                            @if ($item instanceof \App\Models\Pendaftaran)
                                <!-- Tombol Periksa -->
                                <a href="{{ route('umum.create', ['pendaftaran_id' => $item->id]) }}"
                                    class="px-3 py-1 text-white bg-sky-500 rounded text-xs hover:bg-sky-600">
                                    Periksa
                                </a>
                            @else
                                <!-- Tombol Resume dan Detail -->
                                <a href="{{ route('umum.resume', $item->id) }}" target="_blank"
                                    class="px-3 py-1 text-white bg-teal-500 rounded text-xs hover:bg-teal-600">Resume</a>
                                <a href="{{ route('umum.edit', $item->id) }}"
                                    class="px-3 py-1 text-white bg-orange-500 rounded text-xs hover:bg-orange-600">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                      <x-data-notfound :colspan="8" />
                @endforelse
            </tbody>


        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $paginatedData->links() }}
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
    <script>
        document.getElementById('filterTanggal').addEventListener('change', function() {
            const selectedValue = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('filter_tanggal', selectedValue);
            window.location.search = urlParams.toString();
        });
    </script>
@endsection

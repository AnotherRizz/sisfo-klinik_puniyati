<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pembayaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
        }

        .kop h1 {
            margin: 0;
            font-size: 16px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 11px;
        }

        .divider {
            border-top: 2px solid black;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        h3.title {
            font-size: 13px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            word-wrap: break-word;
            text-align: center;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .section-title {
            background-color: #cfe2ff;
            font-weight: bold;
            padding: 6px;
            text-align: left;
            margin-bottom: 6px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
        }

        .total {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="kop">
            <h1>BIDAN PRAKTIK MANDIRI PUNIYATI A.Md Keb</h1>
            <p>Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
            <p>Dusun Kalipejang RT01/RW07, Desa Demakan, Kec. Mojolaban, Kab. Sukoharjo</p>
        </div>
    </div>

    <div class="divider"></div>

    <h3 class="title">Laporan Pembayaran  {{ $judul }}
        {{-- {{ \Carbon\Carbon::createFromDate($tahun, $bulan)->locale('id')->translatedFormat('F Y') }}</h3> --}}

    <table class="table">
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE TRANSAKSI</th>
                <th>NO PERIKSA</th>
                <th>NO RM</th>
                <th>Nama Pasien</th>
                <th>Nama KK</th>
                <th>Jenis Pelayanan</th>
                <th>Obat</th>
                <th>TINDAKAN</th>
                <th>BIAYA TINDAKAN</th>
                <th>BIAYA ADMINISTRASI</th>
                <th>BIAYA KONSULTASI</th>
                <th>BIAYA OBAT</th>
                <th>TANGGAL BAYAR</th>
                <th>JENIS BAYAR</th>
            </tr>
        </thead>
        <tbody>
            @php
                $urutanPelayanan = ['Umum', 'Kesehatan Ibu Hamil', 'Kesehatan Anak', 'Ibu Nifas', 'KB'];
                $counter = 1;
                $totalTindakan = $totalAdministrasi = $totalKonsultasi = $totalObat = 0;

                $allPembayaran = collect($pembayaransByPelayanan)->flatten(1);
            @endphp

            @foreach ($urutanPelayanan as $pelayananName)
                @if (isset($pembayaransByPelayanan[$pelayananName]))
                    @foreach ($pembayaransByPelayanan[$pelayananName] as $item)
                        @php
                            $periksa = $item->pemeriksaanable;
                            $pendaftaran = optional($periksa)->pendaftaran;
                            $pasien = optional($pendaftaran)->pasien;
                            $obatPemeriksaan = optional($periksa)->obatPemeriksaan ?? collect();

                            $subtotalObat = $obatPemeriksaan->sum(function ($ob) {
                                return ($ob->jumlah_obat ?? 0) * ($ob->obat->harga_jual ?? 0);
                            });

                            $obatNamaList =
                                $obatPemeriksaan
                                    ->map(function ($ob) {
                                        return optional($ob->obat)->nama_obat;
                                    })
                                    ->filter()
                                    ->join(', ') ?:
                                '-';

                            // Total keseluruhan
                            $totalTindakan += $item->biaya_tindakan ?? 0;
                            $totalAdministrasi += $item->biaya_administrasi ?? 0;
                            $totalKonsultasi += $item->biaya_konsultasi ?? 0;
                            $totalObat += $subtotalObat;
                        @endphp

                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $item->kd_bayar ?? '-' }}</td>
                            <td>{{ $periksa->nomor_periksa ?? '-' }}</td>
                            <td>{{ $pasien->no_rm ?? '-' }}</td>
                            <td>{{ $pasien->status .'. '.$pasien->nama_pasien ?? '-' }}</td>
                            <td>{{ $pasien->nama_kk ?? '-' }}</td>
                            <td>{{ $pelayananName }}</td>
                            <td>{{ $obatNamaList }}</td>
                            <td>{{ $item->tindakan ?? '-' }}</td>
                            <td>Rp{{ number_format($item->biaya_tindakan ?? 0, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->biaya_administrasi ?? 0, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->biaya_konsultasi ?? 0, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($subtotalObat, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_bayar ?? $item->created_at)->format('d/m/Y') }}
                            </td>
                            <td>{{ $item->jenis_bayar ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
            @php
                $grandTotal = $totalTindakan + $totalAdministrasi + $totalKonsultasi + $totalObat;
            @endphp

            <tr class="font-semibold total bg-gray-100">
                <td colspan="8" style="text-align: right">Jumlah</td>
                <td>Rp{{ number_format($totalTindakan, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($totalAdministrasi, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($totalKonsultasi, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($totalObat, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
            <tr class="font-semibold total bg-gray-100">
                <td colspan="8" style="text-align: right">Total</td>
                <td colspan="7" style="text-align: left">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>

            </tr>

        </tbody>

    </table>

    <div class="footer text-sm text-gray-700">
        <div class="mb-2">
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        </div>
        <div style="margin-top: 0px">
            Sukoharjo, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
            Bidan Praktik Mandiri
        </div>

        <div style="height: 50px;"></div> {{-- Ruang tanda tangan --}}

        <div>
            <strong><u>Puniyati Amd. Keb</u></strong>
        </div>

    </div>

</body>

</html>

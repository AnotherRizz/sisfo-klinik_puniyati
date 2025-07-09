<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pemeriksaan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 40px;
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

        .kop {
            text-align: center;
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
            text-align: left;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
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

        .footer {
            margin-top: 25px;
            font-size: 11px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <div class="kop">
            <h1>BIDAN PRAKTIK MANDIRI PUNIYATI A.Md Keb</h1>
            <p>Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
            <p>Dusun Kalipejang RT01/RW 07, Desa Demakan, Kecamatan Mojolaban, Kabupaten Sukoharjo</p>
        </div>
    </div>

    <div class="divider"></div>
 <h2 class="title font-semibold uppercase">Laporan Data Pemeriksaan {{ $namaPelayanan }} Periode  {{ $namaBulanTahun }}</h2>

    <table class="table table-bordered text-xs">
        <thead>
            <tr>
                <th >NO</th>
                <th >TGL REG</th>
                <th >NO. Periksa</th>
                <th >NO. RM</th>
                <th >NAMA PASIEN</th>
                <th >KELUHAN</th>
                <th >RIW PENYAKIT </th>
                <th >TD (mmHg) </th>
                <th >BB (Kg) </th>
                <th >TB (Cm) </th>
                <th >SUHU (Celcius) </th>
                <th >SATURASI OX </th>
                <th >PEMERIKSAAN PENUNJANG</th>
                <th >DIAGNOSIS </th>
                <th >TINDAKAN </th>
                <th >OBAT </th>
                <th >TGL KEMBALI </th>
                <th >TINDAK LANJUT </th>
            </tr>
        </thead>
        <tbody>
             @foreach ($pemeriksaans as $index => $item)
                            @php
                                $obats = $item->obatPemeriksaan ?? collect();
                                $kdObat = $obats->map(fn($o) => $o->obat->kd_obat ?? '-')->join(', ');
                                $namaObat = $obats->map(fn($o) => $o->obat->nama_obat ?? '-')->join(', ');
                                $dosisCarkai = $obats->map(fn($o) => $o->dosis_carkai ?? '-')->join(', ');
                            @endphp
                            <tr class="border-b">
                                <td >{{ $index + 1 }}</td>
                                <td >
                                    {{ \Carbon\Carbon::parse($item->pendaftaran->tgl_daftar)->translatedFormat('d/m/Y') ?? '-' }}
                                </td>

                                <td >{{ $item->nomor_periksa ?? '-' }}</td>
                                <td >{{ $item->pendaftaran->pasien->no_rm ?? '-' }}</td>
                                <td >{{ $item->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                                <td >{{ $item->keluhan ?? '-' }}</td>
                                <td >{{ $item->riw_penyakit ?? '-' }}</td>
                                <td >{{ $item->td ?? '-' }}</td>
                                <td >{{ $item->bb ?? '-' }}</td>
                                <td >{{ $item->tb ?? '-' }}</td>
                                <td >{{ $item->suhu ?? '-' }}</td>
                                <td >{{ $item->saturasiOx ?? '-' }}</td>
                                <td >{{ $item->pemeriksaan_penunjang ?? '-' }}</td>
                                <td >{{ $item->diagnosa ?? '-' }}</td>
                                <td >{{ $item->tindakan ?? '-' }}</td>
                                <td >{{ $namaObat ?: '-' }}</td>

                                <td >
                                    {{ optional($item->tgl_kembali)->format('d-m-Y') ?? '-' }}
                                </td>
                                <td >{{ $item->tindak_lnjt ?? '-' }}</td>
                            </tr>
                        @endforeach
        </tbody>
    </table>


    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>

</html>

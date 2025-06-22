<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pemeriksaan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            margin: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 100px;
            margin-right: 15px;
            position: absolute;
            top: 10px;
            left: 40px;
        }

        .kop {
            text-align: center;
            flex: ;
        }

        .kop h1 {
            margin: 0;
            font-size: 14px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 11px;
        }

        .divider {
            border-top: 2px solid black;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        h3.title {
            text-align: left;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 13px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>

            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        </div>
        <div class="kop">
            <h1>BIDAN PRAKTIK MANDIRI PUNIYATI A.Md Keb</h1>
            <p>Nomor SIPB: 0026/SIPB/33.11/VI/2019</p>
            <p>Dusun Kalipejang RT01/RW 07, Desa Demakan, Kecamatan Mojolaban, Kabupaten Sukoharjo</p>
        </div>
    </div>

    <div class="divider"></div>

    <h3 class="title">Data Pemeriksaan</h3>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Periksa</th>
                <th>No. Reg</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Tgl Reg</th>
                <th>Kd Bidan</th>
                <th>Kd Pelayanan</th>
                <th>Keluhan</th>
                <th>Riwayat Penyakit</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Kd Obat</th>
                <th>Nama Obat</th>
                <th>Dosis Carkai</th>
                <th>Tgl Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemeriksaan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->no_periksa }}</td>
                    <td>{{ $item->pendaftaran->noreg ?? '-' }}</td>
                    <td>{{ $item->pendaftaran->pasien->no_rm ?? '-' }}</td>
                    <td>{{ $item->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pendaftaran->tgl_daftar)->format('d-m-Y') }}</td>
                    <td>{{ $item->pendaftaran->bidan->kd_bidan ?? '-' }}</td>
                    <td>{{ $item->pendaftaran->pelayanan->kodpel ?? '-' }}</td>
                    <td>{{ $item->keluhan ?? '-' }}</td>
                    <td>{{ $item->riw_penyakit ?? '-' }}</td>
                    <td>{{ $item->diagnosa ?? '-' }}</td>
                    <td>{{ $item->tindakan ?? '-' }}</td>
                    <td>
                        @foreach ($item->obat as $index => $obat)
                            {{ $obat->kd_obat ?? '-' }}{{ $index < $item->obat->count() - 1 ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->obat as $index => $obat)
                            {{ $obat->nama_obat ?? '-' }}{{ $index < $item->obat->count() - 1 ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->obat as $index => $obat)
                            {{ $obat->pivot->dosis_carkai ?? '-' }}{{ $index < $item->obat->count() - 1 ? ', ' : '' }}
                        @endforeach
                    </td>

                    <td>{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d-m-Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>

</html>

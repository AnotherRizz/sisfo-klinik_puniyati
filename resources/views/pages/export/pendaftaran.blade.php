<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title> Data Pendaftaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            float: left;
            width: 90px;
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
        .clear {
            clear: both;
        }
        .divider {
            border-top: 2px solid black;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        h3.title {
            text-align: left;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
        }
        .table th {
            background-color: #e5e5e5;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 11px;
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
        <div class="clear"></div>
    </div>
    <div class="divider"></div>
    <h3 class="title">Data Pendaftaran</h3>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Reg</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Kode Bidan</th>
                <th>Nama Bidan</th>
                <th>Tanggal Daftar</th>
                <th>Jam Daftar</th>
                <th>Kode Pelayanan</th>
                <th>Jenis Pelayanan</th>
                <th>Jenis Kunjungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendaftaran as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->noreg }}</td>
                    <td>{{ $item->pasien->no_rm ?? '-' }}</td>
                    <td>{{ $item->pasien->nama_pasien ?? '-' }}</td>
                    <td>{{ $item->bidan->kd_bidan ?? '-' }}</td>
                    <td>{{ $item->bidan->nama_bidan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_daftar)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jam_daftar)->format('H:i') }}</td>
                    <td>{{ $item->pelayanan->kodpel ?? '-' }}</td>
                    <td>{{ $item->pelayanan->nama_pelayanan ?? '-' }}</td>
                    <td>{{ $item->jenis_kunjungan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>

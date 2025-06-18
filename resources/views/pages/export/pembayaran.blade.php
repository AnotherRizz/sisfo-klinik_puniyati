<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pembayaran</title>
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
        .table th, .table td {
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

    <h3 class="title">Data Pembayaran</h3>

    <table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Bayar</th>
            <th>No. Reg</th>
            <th>No. RM</th>
            <th>Nama Pasien</th>
            <th>Nama Bidan</th>
            <th>Tanggal Bayar</th>
            <th>Administrasi</th>
            <th>Biaya Administrasi</th>
            <th>Tindakan</th>
            <th>Biaya Tindakan</th>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Biaya Obat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pembayaran as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kd_bayar }}</td>
                <td>{{ $item->pemeriksaan->pendaftaran->noreg ?? '-' }}</td>
                <td>{{ $item->pemeriksaan->pendaftaran->pasien->no_rm ?? '-' }}</td>
                <td>{{ $item->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
                <td>{{ $item->pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_bayar)->format('d-m-Y') }}</td>
                <td>{{ $item->administrasi }}</td>
                <td>Rp {{ number_format($item->biaya_administrasi, 0, ',', '.') }}</td>
                <td>{{ $item->tindakan }}</td>
                <td>Rp {{ number_format($item->biaya_tindakan, 0, ',', '.') }}</td>
                <td>{{ $item->pemeriksaan->obat->kd_obat ?? '-' }}</td>
                <td>{{ $item->pemeriksaan->obat->nama_obat ?? '-' }}</td>
                <td>Rp {{ number_format($item->pemeriksaan->obat->harga_jual, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>

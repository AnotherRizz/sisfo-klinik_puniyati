<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }
        
         .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 0;
            left: -2px;
            width: 80px;
        }
        .kop {
            text-align: center;
            margin: 0px 0px 0px 60px;
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
            margin: 10px 0;
        }
        .info-table {
            width: 100%;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .biaya-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .biaya-table th, .biaya-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .biaya-table th {
            background-color: #ccc;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
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
    <h4 style="text-align: center; margin-bottom: 10px;">BUKTI PEMBAYARAN</h4>

    <table class="info-table">
        <tr>
            <td>No. RM</td>
            <td>: {{ $pembayaran->pemeriksaan->pendaftaran->pasien->no_rm ?? '-' }}</td>
            <td>No. Registrasi</td>
            <td>: {{ $pembayaran->pemeriksaan->pendaftaran->noreg ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Pasien</td>
            <td>: {{ $pembayaran->pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
            <td>Tanggal Pemeriksaan</td>
            <td>: {{ \Carbon\Carbon::parse($pembayaran->pemeriksaan->tgl_periksa ?? now())->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $pembayaran->pemeriksaan->pendaftaran->pasien->alamat ?? '-' }}</td>
            <td>Tanggal Cetak</td>
            <td>: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td>: {{ \Carbon\Carbon::parse($pembayaran->pemeriksaan->pendaftaran->pasien->tgl_lahir ?? now())->format('d-m-Y') }}</td>
            <td>Nama Bidan</td>
            <td>: {{ $pembayaran->pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
        </tr>
    </table>

    <table class="biaya-table">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th>TOTAL</th>
            </tr>
        </thead>
      <tbody>
    <tr>
        <td>ADMINISTRASI</td>
        <td>Rp {{ number_format($pembayaran->biaya_administrasi, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>TINDAKAN DAN LAYANAN MEDIS</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>{{ $pembayaran->tindakan }}</td>
        <td>Rp {{ number_format($pembayaran->biaya_tindakan, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>{{ $pembayaran->pemeriksaan->obat->nama_obat ?? 'OBAT' }}</td>
        <td>Rp {{ number_format($pembayaran->pemeriksaan->obat->harga_jual ?? 0, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>KONSULTASI</td>
        <td>Rp {{ number_format($pembayaran->biaya_konsultasi ?? 0, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>TOTAL</strong></td>
        <td>
            <?php
                $total = 
                    ($pembayaran->biaya_administrasi ?? 0) + 
                    ($pembayaran->biaya_tindakan ?? 0) + 
                    ($pembayaran->pemeriksaan->obat->harga_jual ?? 0) + 
                    ($pembayaran->biaya_konsultasi ?? 0);
            ?>
            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </td>
    </tr>
</tbody>

    </table>

    <div class="footer">
        <p><strong>Bidan/Kasir</strong></p>
    </div>
</body>
</html>

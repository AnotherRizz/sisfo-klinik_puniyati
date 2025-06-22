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
        .table th, .table td {
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
<h3 class="title">Laporan Pemeriksaan - {{ \Carbon\Carbon::createFromDate($tahun, $bulan)->locale('id')->translatedFormat('F Y') }}</h3>
<p style="font-size: 15px; margin-bottom: 10px;">Jenis Pelayanan: <strong>{{ $namaPelayanan }}</strong></p>

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
        @foreach ($pemeriksaans as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->no_periksa ?? '-' }}</td>
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
                
                {{-- Kode Obat --}}
                <td>
                    {{ $item->obat->pluck('kd_obat')->join(', ') ?? '-' }}
                </td>
                
                {{-- Nama Obat --}}
                <td>
                    {{ $item->obat->pluck('nama_obat')->join(', ') ?? '-' }}
                </td>
                
                {{-- Dosis Carkai --}}
                <td>
                    {{ $item->obat->map(fn($o) => $o->pivot->dosis_carkai)->join(', ') ?? '-' }}
                </td>
                
                <td>{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d-m-Y') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<div class="footer">
    Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Identitas Berobat</title>
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
            margin: 10px 0 15px;
        }

        .box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 10px;
        }

        .box table {
            width: 100%;
        }

        .box td {
            padding: 3px 0;
        }

        .cut-line {
            border-top: 1px dashed #000;
            margin-top: 30px;
            text-align: left;
            font-weight: bold;
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
</div>

<div class="divider"></div>
<h3 style="text-transform: uppercase;">Kartu Identitas Berobat</h3>

<div class="box">
    <table>
        <tr><td>No. RM</td><td>: {{ $pasien->no_rm }}</td></tr>
        <tr><td>Nama Pasien</td><td>: {{ $pasien->status. '. '. $pasien->nama_pasien }}</td></tr>
        <tr><td>Tempat Lahir</td><td>: {{ $pasien->tempt_lahir }}</td></tr>
        <tr><td>Tanggal Lahir</td><td>: {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') }}</td></tr>
        <tr><td>Umur</td><td>: {{ $pasien->umur }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $pasien->jenis_kelamin }}</td></tr>
        <tr><td>Agama</td><td>: {{ $pasien->agama }}</td></tr>
        <tr><td>Gol. Darah</td><td>: {{ $pasien->golda }}</td></tr>
        <tr><td>No. Telpon</td><td>: {{ $pasien->no_tlp }}</td></tr>
    </table>
</div>

<div class="cut-line">Potong Disini</div>

</body>
</html>

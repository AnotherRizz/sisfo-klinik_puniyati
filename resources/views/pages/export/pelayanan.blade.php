<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title> Data Pelayanan</title>
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

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 5%;
            max-width: 40px;
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
    <h3 class="title">Data Pelayanan</h3>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pelayanan</th>
                <th>Nama Pelayanan</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($pelayanan as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->kodpel ?? '-' }}</td>
                    <td>{{ $item->nama_pelayanan ?? '-' }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>

</html>

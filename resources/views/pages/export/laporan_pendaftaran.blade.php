<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            text-align: center;
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
            margin: 10px 0 15px;
        }

        h3.title {
            font-size: 13px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            word-wrap: break-word;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .section-title {
            background-color: #e0f2ff;
            font-weight: bold;
            text-align: left;
            padding: 6px;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
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
            <p>Dusun Kalipejang RT01/RW07, Desa Demakan, Kec. Mojolaban, Kab. Sukoharjo</p>
        </div>
    </div>

    <div class="divider"></div>

    <h3 class="title">Laporan Pendaftaran {{ $judul }}</h3>

    @php
        $urutan = ['Umum', 'Kesehatan Ibu Hamil', 'Kesehatan Anak', 'Ibu Nifas', 'KB'];
        $no = 1;
    @endphp
    <table>
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
            @foreach ($urutan as $pelayanan)
                @if (isset($pendaftaransByPelayanan[$pelayanan]))
                    @foreach ($pendaftaransByPelayanan[$pelayanan] as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->noreg }}</td>
                            <td>{{ $item->pasien->no_rm ?? '-' }}</td>
                            <td style="text-align: left;">{{ $item->pasien->nama_pasien ?? '-' }}</td>
                            <td>{{ $item->bidan->kd_bidan ?? '-' }}</td>
                            <td style="text-align: left;">{{ $item->bidan->nama_bidan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_daftar)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->jam_daftar)->format('H:i') }}</td>
                            <td>{{ $item->pelayanan->kodpel ?? '-' }}</td>
                            <td style="text-align: left;">{{ $item->pelayanan->nama_pelayanan ?? '-' }}</td>
                            <td>{{ $item->jenis_kunjungan }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        
        </tbody>
    </table>

    <div class="footer">
          Total Seluruh Pendaftaran: {{ $allpendaftaran->count() }} Data
    </div>

  <div class="footer mt-10 text-sm text-gray-700">
         <div class="mb-2" >
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        </div>
        <div style="margin-top: 15px">
            Sukoharjo, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
            Bidan Praktik Mandiri
        </div>

        <div style="height: 80px;"></div> {{-- Ruang tanda tangan --}}

        <div>
            <strong><u>Puniyati Amd. Keb</u></strong>
        </div>
       
    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pemeriksaan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 10px;
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
            margin-top: 5px;
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
    <h2 class="title font-semibold uppercase">Laporan Data Pemeriksaan {{ $namaPelayanan }}  {{ $judul }}
    </h2>

    <table class="table table-bordered text-xs">
        <thead>
            <tr>
                <th >NO</th>
                <th >TGL REG</th>
                <th >NO. Periksa</th>
                <th >NO. RM</th>
                <th >NAMA PASIEN</th>
                <th >NAMA KK</th>
                <th >KELUHAN</th>
                <th >UMUR KEHAMILAN</th>
                <th >TD (mmHg) </th>
                <th >BB (Kg) </th>
                <th >TB (Cm) </th>
                <th >GPA</th>
                <th >LAB </th>
                <th >JENIS RESTI</th>
                <th >RIW TT </th>
                <th >HPHT </th>
                <th >HPL </th>
                <th >DIAGNOSIS </th>
                <th >TINDAKAN </th>
                <th >OBAT </th>
                <th >TGL KEMBALI </th>
                <th >TINDAK LANJUT </th>
                <th >NO. HP </th>
            </tr>
        </thead>
       <tbody>
    @foreach ($pemeriksaans as $index => $item)
        @php
            $obats = $item->obatPemeriksaan ?? collect();

            $suplemenList = $obats->where('vitamin_suplemen', 'ya')->map(fn($o) =>
                '(' . ($o->jumlah_obat ?? '-') . ') ' .
                ($o->obat->nama_obat ?? '-') .
                ' (' . ($o->dosis_carkai ?? '-') . ')'
            )->join(', ');

            $obatBiasaList = $obats->where('vitamin_suplemen', 'tidak')->map(fn($o) =>
                '(' . ($o->jumlah_obat ?? '-') . ') ' .
                ($o->obat->nama_obat ?? '-') .
                ' (' . ($o->dosis_carkai ?? '-') . ')'
            )->join(', ');
        @endphp

        <tr class="border-b">
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->pendaftaran->tgl_daftar ? \Carbon\Carbon::parse($item->pendaftaran->tgl_daftar)->translatedFormat('d/m/Y') : '-' }}</td>
            <td>{{ $item->nomor_periksa ?? '-' }}</td>
            <td>{{ $item->pendaftaran->pasien->no_rm ?? '-' }}</td>
            <td>{{ $item->pendaftaran->pasien->nama_pasien ?? '-' }}</td>
            <td>{{ $item->pendaftaran->pasien->nama_kk ?? '-' }}</td>
            <td>{{ $item->keluhan ?? '-' }}</td>
            <td>{{ $item->umr_hamil ? $item->umr_hamil . ' Minggu' : '-' }}</td>
            <td>{{ $item->td ?? '-' }}</td>
            <td>{{ $item->bb ?? '-' }}</td>
            <td>{{ $item->tb ?? '-' }}</td>
            <td>{{ $item->gpa ?? '-' }}</td>
            <td>{{ $item->lab ?? '-' }}</td>
            <td>{{ $item->resti ?? '-' }}</td>
            <td>{{ $item->riwayat_TT ?? '-' }}</td>
            <td>{{ $item->hpht ? \Carbon\Carbon::parse($item->hpht)->translatedFormat('d/m/Y') : '-' }}</td>
            <td>{{ $item->hpl ? \Carbon\Carbon::parse($item->hpl)->translatedFormat('d/m/Y') : '-' }}</td>
            <td>{{ $item->diagnosa ?? '-' }}</td>
            <td>{{ $item->tindakan ?? '-' }}</td>

            {{-- Obat dan Suplemen --}}
            <td>
                @if ($suplemenList)
                    <div><strong>Suplemen:</strong> {{ $suplemenList }}</div>
                @endif
                @if ($obatBiasaList)
                    <div><strong>Obat:</strong> {{ $obatBiasaList }}</div>
                @endif
                @if (!$suplemenList && !$obatBiasaList)
                    <div>-</div>
                @endif
            </td>

            <td>{{ $item->tgl_kembali ? \Carbon\Carbon::parse($item->tgl_kembali)->translatedFormat('d/m/Y') : '-' }}</td>
            <td>{{ $item->tindak_lnjt ?? '-' }}</td>
            <td>{{ $item->pendaftaran->pasien->no_tlp ?? '-' }}</td>
        </tr>
    @endforeach
</tbody>

    </table>


    <div class="footer text-sm text-gray-700">
         <div class="mb-2" >
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Resume Medis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .left {
            background-color: rgb(211, 211, 211);
            width: 40%;
        }

        .header,
        .section {
            margin-bottom: 10px;
        }

        .bordered td,
        .bordered th {
            border: 1px solid black;
            padding: 4px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .subjudul {
            text-align: center;
            font-size: 12px;
        }

        .data-pasien {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .data-pasien table {
            width: 100%;
            table-layout: fixed;
            font-size: 12px;
        }

        .data-pasien td {
            vertical-align: top;
            padding: 2px 4px;
        }

        .data-pasien td.label {
            width: 30%;
            font-weight: bold;
        }

        .data-pasien td.separator {
            width: 5px;
        }

        .data-pasien td.value {
            width: 35%;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td width="6%">
                    <img src="{{ public_path('images/logo.png') }}" width="90">
                </td>
                <td width="80%">
                    <div class="judul">BIDAN PRAKTIK MANDIRI PUNIYATI A.Md Keb</div>
                    <div class="subjudul">Nomor SIPB: 0026/SIPB/33.11/VI/2019</div>
                    <div class="subjudul">Dusun Kalipejang RT01/RW 07, Desa Demakan, Kecamatan Mojolaban, Kabupaten
                        Sukoharjo</div>
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="judul">Resume Medis</div>
    <div class="data-pasien">
        <table>
            <tr>
                <td class="label">No. RM</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->pasien->no_rm ?? '-' }}</td>

                <td class="label">No. Registrasi</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->noreg ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Pasien</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>

                <td class="label">Tanggal Periksa</td>
                <td class="separator">:</td>
                <td class="value">: {{ \Carbon\Carbon::parse($pemeriksaan->created_at)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value">:
                    {{ \Carbon\Carbon::parse($pemeriksaan->pendaftaran->pasien->tgl_lahir)->format('d-m-Y') }}</td>

                <td class="label">Tanggal Cetak</td>
                <td class="separator">:</td>
                <td class="value">: {{ now()->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->pasien->jenis_kelamin ?? '-' }}</td>

                <td class="label">Nama Bidan</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->bidan->nama_bidan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->pasien->alamat ?? '-' }}</td>

                <td class="label">Pelayanan</td>
                <td class="separator">:</td>
                <td class="value">: {{ $pemeriksaan->pendaftaran->pelayanan->nama_pelayanan ?? '-' }}</td>
            </tr>
        </table>
    </div>
    <hr>
    <h4 style="margin-top: 10px;">PEMERIKSAAN</h4>
<table class="bordered">
    <tr>
        <td class="left">Keluhan</td>
        <td>{{ $pemeriksaan->keluhan ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Riwayat Penyakit</td>
        <td>{{ $pemeriksaan->riw_penyakit ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Tensi Darah (mm/Hg)</td>
        <td>{{ $pemeriksaan->td ? $pemeriksaan->td . ' mmHg' : '-' }}</td>
    </tr>
    <tr>
        <td class="left">Alergi</td>
        <td>{{ $pemeriksaan->alergi ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Hari Pertama Haid Ibu Hamil</td>
        <td>{{ $pemeriksaan->hpht 
            ? \Carbon\Carbon::parse($pemeriksaan->hpht)->locale('id')->translatedFormat('d F Y') 
            : '-' }}
        </td>
    </tr>
    <tr>
        <td class="left">Jumlah Anak</td>
        <td>{{ $pemeriksaan->jmlhanak ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Tanggal Pasang</td>
        <td>{{ $pemeriksaan->tglpasang 
            ? \Carbon\Carbon::parse($pemeriksaan->tglpasang)->locale('id')->translatedFormat('d F Y') 
            : '-' }}
        </td>
    </tr>
    <tr>
        <td class="left">Metode KB</td>
        <td>{{ $pemeriksaan->metode_kb ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Edukasi</td>
        <td>{{ $pemeriksaan->edukasi ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Intervensi</td>
        <td>{{ $pemeriksaan->intervensi ?? '-' }}</td>
    </tr>
    <tr>
        <td class="left">Tindak Lanjut</td>
        <td>
            @if ($pemeriksaan->tindak_lnjt === 'Tidak Dirujuk')
                Tidak dirujuk
            @elseif ($pemeriksaan->tindak_lnjt === 'Puskesmas')
                Rujukan ke Puskesmas
            @elseif ($pemeriksaan->tindak_lnjt === 'Klinik')
                Rujukan ke Klinik
            @elseif ($pemeriksaan->tindak_lnjt === 'Rujuk Spesialis Obsgyn')
                Rujuk Spesialis Obsgyn
            @elseif ($pemeriksaan->tindak_lnjt === 'Rumah Sakit')
                Rujukan ke Rumah Sakit
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <td class="left">Tanggal Kembali</td>
        <td>{{ $pemeriksaan->tgl_kembali 
            ? \Carbon\Carbon::parse($pemeriksaan->tgl_kembali)->locale('id')->translatedFormat('d F Y') 
            : '-' }}
        </td>
    </tr>
    <tr>
        <td class="left">Obat dan Dosis</td>
        <td>
            @forelse ($pemeriksaan->obatPemeriksaan as $o)
                <div>
                    {{ $o->obat->nama_obat ?? '-' }} ({{ $o->dosis_carkai ?? '-' }})
                </div>
            @empty
                Tidak ada obat
            @endforelse
        </td>
    </tr>
</table>

</body>

</html>

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
                 <td class="value">: {{ $pemeriksaan->pendaftaran->pasien->status .'. '. $pemeriksaan->pendaftaran->pasien->nama_pasien ?? '-' }}</td>

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
            <td class="left">Riwayat TT (riw. Imunisasi Tetanus Toksoid)</td>
            <td>{{ $pemeriksaan->riwayat_TT ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Tensi Darah (mm/Hg)</td>
            <td>{{ $pemeriksaan->td ? $pemeriksaan->td . ' mmHg' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Berat Badan (Kg)</td>
            <td>{{ $pemeriksaan->bb ? $pemeriksaan->bb . ' Kg' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Tinggi Badan (Cm)</td>
            <td>{{ $pemeriksaan->tb ? $pemeriksaan->tb . ' Cm' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Suhu (Celcius)</td>
            <td>{{ $pemeriksaan->suhu ? $pemeriksaan->suhu . ' °C' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Saturasi Oksigen</td>
            <td>{{ $pemeriksaan->saturasiOx ? $pemeriksaan->saturasiOx . ' %' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Tinggi Fundus (Cm)</td>
            <td>{{ $pemeriksaan->tifu ? $pemeriksaan->tifu . ' Cm' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Denyut Nadi Ibu Hamil</td>
            <td>{{ $pemeriksaan->nadi ? $pemeriksaan->nadi . ' bpm' : '-' }}</td>
        </tr>

        <tr>
            <td class="left">Lingkar Lengan Atas (LILA)</td>
            <td>{{ $pemeriksaan->lila ? $pemeriksaan->lila . ' cm' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Hari Pertama Haid Ibu Hamil</td>
            <td>{{ $pemeriksaan->hpht ? \Carbon\Carbon::parse($pemeriksaan->hpht)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="left">Hari Perkiraan Lahir Bayi</td>
            <td>{{ $pemeriksaan->hpl ? \Carbon\Carbon::parse($pemeriksaan->hpl)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="left">Gravida, Paritas, Abortus Ibu Hamil</td>
            <td>{{ $pemeriksaan->gpa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Riwayat Kehamilan dan Kesehatan</td>
            <td>{{ $pemeriksaan->riwayatkehamilankesehatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Umur Kehamilan</td>
            <td>{{ $pemeriksaan->umr_hamil ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Tinggi Fundus Uteri (TFU)</td>
            <td>{{ $pemeriksaan->tifu ? $pemeriksaan->tifu . ' cm' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Denyut Jantung Janin</td>
            <td>{{ $pemeriksaan->djj ? $pemeriksaan->djj . ' bpm' : '-' }}</td>
        </tr>
        <tr>
            <td class="left">Letak Janin</td>
            <td>{{ $pemeriksaan->ltkjanin ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Kontraksi Uterus</td>
            <td>{{ $pemeriksaan->ktrkuterus ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Refleks Patela (Refla)</td>
            <td>{{ $pemeriksaan->refla ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Pemeriksaan Lab</td>
            <td>{{ $pemeriksaan->lab ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Keterangan Resiko Tinggi</td>
            <td>{{ $pemeriksaan->resti ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Diagnosa</td>
            <td>{{ $pemeriksaan->diagnosa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Intervensi</td>
            <td>{{ $pemeriksaan->intervensi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left">Tindak Lanjut</td>
            <td>
                @switch($pemeriksaan->tindak_lnjt)
                    @case('Tidak Dirujuk')
                        Tidak Dirujuk
                    @break

                    @case('Puskesmas')
                        Rujukan ke Puskesmas
                    @break

                    @case('Klinik')
                        Rujukan ke Klinik
                    @break

                    @case('Rujuk Dokter Spesialis Obsygin')
                        Rujukan ke Dokter Spesialis Obsygin
                    @break

                    @case('Rumah Sakit')
                        Rujukan ke Rumah Sakit
                    @break

                    @default
                        -
                @endswitch
            </td>
        </tr>
        <tr>
            <td class="left">Tanggal Kembali</td>
            <td>{{ $pemeriksaan->tgl_kembali ? \Carbon\Carbon::parse($pemeriksaan->tgl_kembali)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
       <tr>
    <td class="left">Vitamin/Suplemen</td>
    <td>
        @php
            $suplemen = $pemeriksaan->obatPemeriksaan->where('vitamin_suplemen', 'ya');
        @endphp

        @forelse ($suplemen as $o)
            <div>
                ({{ $o->jumlah_obat ?? '-' }}) {{ $o->obat->nama_obat ?? '-' }} ({{ $o->dosis_carkai ?? '-' }})
            </div>
        @empty
            Tidak ada suplemen
        @endforelse
    </td>
</tr>

<tr>
    <td class="left">Obat </td>
    <td>
        @php
            $obatBiasa = $pemeriksaan->obatPemeriksaan->where('vitamin_suplemen', 'tidak');
        @endphp

        @forelse ($obatBiasa as $o)
            <div>
                ({{ $o->jumlah_obat ?? '-' }}) {{ $o->obat->nama_obat ?? '-' }} ({{ $o->dosis_carkai ?? '-' }})
            </div>
        @empty
            Tidak ada obat 
        @endforelse
    </td>
</tr>

    </table>

</body>

</html>

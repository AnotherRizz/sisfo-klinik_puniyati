<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pembayaran;
use App\Models\PemeriksaanUmum;
use App\Models\PemeriksaanKb;
use App\Models\PemeriksaanKiaIbuHamil;
use App\Models\PemeriksaanKiaAnak;
use App\Models\PemeriksaanIbuNifas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelayanans = Pelayanan::all();
        $pasiens = Pasien::all();
        return view('pages.laporan.index', compact('pelayanans', 'pasiens'));
    }

    /**
     * Export laporan pendaftaran to PDF.
     */
 public function exportPendaftaran(Request $request)
{
    $bulanInput = $request->input('bulan'); // ex: "2025-06"
    [$tahun, $bulan] = explode('-', $bulanInput);

    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
        ->where('pelayanan_id', $jenisPelayanan)
        ->whereMonth('tgl_daftar', $bulan)
        ->whereYear('tgl_daftar', $tahun)
        ->orderBy('tgl_daftar')
        ->get();

    $namaPelayanan = optional($pendaftarans->first()->pelayanan)->nama_pelayanan ?? '-';
    // ✅ Cek apakah data kosong
    if ($pendaftarans->isEmpty()) {
        return redirect()->back()->with('error', `Data pendaftaran tidak ditemukan untuk bulan {$bulan} dan pelayanan {$namaPelayanan} yang dipilih.`);
    }

    $namaPelayanan = optional($pendaftarans->first()->pelayanan)->nama_pelayanan ?? '-';

    $pdf = Pdf::loadView('pages.export.laporan_pendaftaran', compact(
        'pendaftarans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ));

    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

   return $pdf->download("laporan_pendaftaran_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}

//preview pendaftaran
public function previewPendaftaran(Request $request)
{
    $bulanInput = $request->input('bulan'); // ex: "2025-06"
    [$tahun, $bulan] = explode('-', $bulanInput);

    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
        ->where('pelayanan_id', $jenisPelayanan)
        ->whereMonth('tgl_daftar', $bulan)
        ->whereYear('tgl_daftar', $tahun)
        ->orderBy('tgl_daftar')
        ->get();

    // Ambil nama pelayanan dari tabel Pelayanan berdasarkan ID
    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';

    if ($pendaftarans->isEmpty()) {
        // Konversi bulan ke format nama bulan Indonesia
        $namaBulan = Carbon::createFromFormat('Y-m', $request->bulan)->locale('id')->translatedFormat('F Y');

        return redirect()->back()->with('error', "Data pendaftaran untuk bulan $namaBulan dan pelayanan $namaPelayanan tidak ditemukan.");
    }

    return view('pages.export.preview.pendaftaran', compact(
        'pendaftarans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ));
}

// // export pemeriksaan berdasarkan pelayanan
// public function export(Request $request)
// {
//     // Ambil parameter nama_pelayanan dari request
//     $namaPelayanan = $request->get('nama_pelayanan', 'Umum'); // Default: 'Umum'

//     // Query data pemeriksaan berdasarkan nama_pelayanan
//     $pemeriksaan = Pemeriksaan::with([
//         'pendaftaran.pasien',
//         'pendaftaran.bidan',
//         'pendaftaran.pelayanan',
//         'obat'
//     ])
//     ->whereHas('pendaftaran.pelayanan', function ($query) use ($namaPelayanan) {
//         $query->where('nama_pelayanan', $namaPelayanan);
//     })
//     ->get();

//     // Generate PDF
//     $pdf = Pdf::loadView('pages.export.pemeriksaan.all', compact('pemeriksaan', 'namaPelayanan'))
//               ->setPaper('A4', 'landscape');

//     return $pdf->stream("data_pemeriksaan_{$namaPelayanan}.pdf");
// }




    /**
     * Export laporan pemeriksaan to PDF.
     */
public function exportPemeriksaan(Request $request)
{
    [$tahun, $bulan] = explode('-', $request->input('bulan'));
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pemeriksaans = $this->getAllPemeriksaanFiltered($bulan, $tahun, $jenisPelayanan);

    if ($pemeriksaans->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk bulan dan jenis pelayanan yang dipilih.');
    }

    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';
    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    $pdf = Pdf::loadView('pages.export.laporan_pemeriksaan', compact(
        'pemeriksaans', 'bulan', 'tahun', 'namaPelayanan'
    ))->setPaper('a4', 'landscape');

    return $pdf->download("laporan_pemeriksaan_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}


public function previewPemeriksaan(Request $request)
{
    [$tahun, $bulan] = explode('-', $request->input('bulan'));
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pemeriksaans = $this->getAllPemeriksaanFiltered($bulan, $tahun, $jenisPelayanan);

    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';

    if ($pemeriksaans->isEmpty()) {
        $namaBulan = Carbon::createFromFormat('Y-m', $request->input('bulan'))->translatedFormat('F Y');
        return back()->with('error', "Data pemeriksaan untuk bulan $namaBulan dan pelayanan $namaPelayanan tidak ditemukan.");
    }

    return view('pages.export.preview.pemeriksaan', compact(
        'pemeriksaans', 'bulan', 'tahun', 'namaPelayanan'
    ));
}


private function getAllPemeriksaanFiltered($bulan, $tahun, $jenisPelayanan)
{
    return collect()
        ->merge(PemeriksaanUmum::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])
            ->whereMonth('tgl_kembali', $bulan)
            ->whereYear('tgl_kembali', $tahun)
            ->whereHas('pendaftaran', fn($q) => $q->where('pelayanan_id', $jenisPelayanan))
            ->get())
        ->merge(PemeriksaanKb::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])
            ->whereMonth('tgl_kembali', $bulan)
            ->whereYear('tgl_kembali', $tahun)
            ->whereHas('pendaftaran', fn($q) => $q->where('pelayanan_id', $jenisPelayanan))
            ->get())
        ->merge(PemeriksaanKiaIbuHamil::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])
            ->whereMonth('tgl_kembali', $bulan)
            ->whereYear('tgl_kembali', $tahun)
            ->whereHas('pendaftaran', fn($q) => $q->where('pelayanan_id', $jenisPelayanan))
            ->get())
        ->merge(PemeriksaanKiaAnak::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])
            ->whereMonth('tgl_kembali', $bulan)
            ->whereYear('tgl_kembali', $tahun)
            ->whereHas('pendaftaran', fn($q) => $q->where('pelayanan_id', $jenisPelayanan))
            ->get())
        ->merge(PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])
            ->whereMonth('tgl_kembali', $bulan)
            ->whereYear('tgl_kembali', $tahun)
            ->whereHas('pendaftaran', fn($q) => $q->where('pelayanan_id', $jenisPelayanan))
            ->get())
        ->sortBy('tgl_kembali')
        ->values();
}




    /**
     * Export laporan pembayaran to PDF.
     */
public function exportPembayaran(Request $request)
{
    $request->validate([
        'bulan' => 'required|date_format:Y-m',
        'jenis_pelayanan' => 'required|exists:pelayanan,id',
    ]);

    [$tahun, $bulan] = explode('-', $request->input('bulan'));
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pembayarans = Pembayaran::with([
        'pemeriksaanable.pendaftaran.pasien',
        'pemeriksaanable.pendaftaran.bidan',
        'pemeriksaanable.pendaftaran.pelayanan',
        'pemeriksaanable.obat'
    ])
    ->whereMonth('created_at', $bulan)
    ->whereYear('created_at', $tahun)
    ->orderBy('created_at')
    ->get()
    ->filter(function ($item) use ($jenisPelayanan) {
        return optional($item->pemeriksaanable->pendaftaran)->pelayanan_id == $jenisPelayanan;
    })
    ->values(); // reset index array

    if ($pembayarans->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk bulan dan jenis pelayanan yang dipilih.');
    }

    $namaPelayanan = optional($pembayarans->first()->pemeriksaanable->pendaftaran->pelayanan)->nama_pelayanan ?? 'Umum';

    Carbon::setLocale('id');
    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    $pdf = Pdf::loadView('pages.export.laporan_pembayaran', compact(
        'pembayarans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ))->setPaper('a4', 'landscape');

    return $pdf->download("laporan_pembayaran_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}




//preview pembayaran
public function previewpembayaran(Request $request)
{
    [$tahun, $bulan] = explode('-', $request->input('bulan'));
    $jenisPelayanan = $request->input('jenis_pelayanan');

    // Ambil semua pembayaran dalam bulan & tahun
    $pembayarans = Pembayaran::with([
        'pemeriksaanable.pendaftaran.pasien',
        'pemeriksaanable.pendaftaran.bidan',
        'pemeriksaanable.pendaftaran.pelayanan',
        'pemeriksaanable.obat'
    ])
    ->whereMonth('created_at', $bulan)
    ->whereYear('created_at', $tahun)
    ->orderBy('created_at')
    ->get();

    // Filter manual berdasarkan jenis_pelayanan
    $pembayarans = $pembayarans->filter(function ($pembayaran) use ($jenisPelayanan) {
        return optional($pembayaran->pemeriksaanable->pendaftaran)->pelayanan_id == $jenisPelayanan;
    });

    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';

    if ($pembayarans->isEmpty()) {
        $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)
            ->locale('id')->translatedFormat('F Y');
        return back()->with('error', "Data pembayaran untuk bulan $namaBulan dan pelayanan $namaPelayanan tidak ditemukan.");
    }

    return view('pages.export.preview.pembayaran', compact(
        'pembayarans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ));
}


public function export(Request $request)
    {
        $nama_pelayanan = $request->nama_pelayanan;

        $pemeriksaan = match ($nama_pelayanan) {
            'Umum' => PemeriksaanUmum::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'KB' => PemeriksaanKb::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'KIA Ibu Hamil' => PemeriksaanKiaIbuHamil::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'Ibu Nifas' => PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'KIA Anak' => PemeriksaanKiaAnak::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            default => collect(),
        };

        $pdf = Pdf::loadView('pages.export.pemeriksaan.all', compact('pemeriksaan', 'nama_pelayanan'))->setPaper('A4', 'landscape');

        return $pdf->download('laporan-pemeriksaan-'.$nama_pelayanan.'.pdf');
    }









}
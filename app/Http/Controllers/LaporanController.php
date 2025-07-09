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
    $request->validate([
        'bulan' => 'required|date_format:Y-m',
    ]);

    [$tahun, $bulan] = explode('-', $request->bulan);

    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
        ->whereMonth('tgl_daftar', $bulan)
        ->whereYear('tgl_daftar', $tahun)
        ->orderBy('tgl_daftar')
        ->get();

    if ($pendaftarans->isEmpty()) {
        $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)->locale('id')->translatedFormat('F Y');
        return redirect()->back()->with('error', "Data pendaftaran untuk bulan $namaBulan tidak ditemukan.");
    }

    // Group by jenis pelayanan
    $pendaftaransByPelayanan = $pendaftarans->groupBy(fn($item) => $item->pelayanan->nama_pelayanan ?? 'Lainnya');

    $pdf = Pdf::loadView('pages.export.laporan_pendaftaran', [
        'pendaftaransByPelayanan' => $pendaftaransByPelayanan,
        'bulan' => $bulan,
        'tahun' => $tahun
    ])->setPaper('a4', 'landscape');

    $namaBulanTahun = \Carbon\Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    return $pdf->stream("laporan_pendaftaran{$namaBulanTahun}.pdf");
}


//preview pendaftaran
public function previewPendaftaran(Request $request)
{
    $request->validate([
        'bulan' => 'required|date_format:Y-m',
    ]);

    [$tahun, $bulan] = explode('-', $request->bulan);

    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
        ->whereMonth('tgl_daftar', $bulan)
        ->whereYear('tgl_daftar', $tahun)
        ->orderBy('tgl_daftar')
        ->get();

    if ($pendaftarans->isEmpty()) {
        $namaBulan = Carbon::createFromFormat('Y-m', $request->bulan)->locale('id')->translatedFormat('F Y');
        return redirect()->back()->with('error', "Data pendaftaran untuk bulan $namaBulan tidak ditemukan.");
    }

    // Kelompokkan berdasarkan jenis pelayanan (nama)
    $pendaftaransByPelayanan = $pendaftarans->groupBy(fn($item) => $item->pelayanan->nama_pelayanan ?? 'Lainnya');

    return view('pages.export.preview.pendaftaran', [
        'pendaftaransByPelayanan' => $pendaftaransByPelayanan,
        'bulan' => $bulan,
        'tahun' => $tahun
    ]);
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

    $pemeriksaans = $this->getPemeriksaanByJenisPelayanan($jenisPelayanan, $bulan, $tahun);

    if ($pemeriksaans->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk bulan dan jenis pelayanan yang dipilih.');
    }

    $namaPelayanan = ucwords(str_replace('_', ' ', $jenisPelayanan));
    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    // Tentukan view berdasarkan jenis pelayanan
    switch (strtolower($jenisPelayanan)) {
        case 'umum':
            $view = 'pages.export.pdf.umum';
            break;
        case 'kb':
            $view = 'pages.export.pdf.kb';
            break;
        case 'kia_ibu_hamil':
            $view = 'pages.export.pdf.kia_ibu_hamil';
            break;
        case 'kia_anak':
            $view = 'pages.export.pdf.kia_anak';
            break;
        case 'ibu_nifas':
            $view = 'pages.export.pdf.ibu_nifas';
            break;
        default:
            $view = 'pages.export.pdf.default'; // fallback jika ada kesalahan
            break;
    }

    // Generate PDF dari view yang sesuai
    $pdf = Pdf::loadView($view, compact('pemeriksaans', 'bulan', 'tahun', 'namaPelayanan', 'namaBulanTahun'))
        ->setPaper('f4', 'landscape');

    return $pdf->stream("laporan_pemeriksaan_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}




public function previewPemeriksaan(Request $request)
{
    [$tahun, $bulan] = explode('-', $request->input('bulan'));
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pemeriksaans = $this->getPemeriksaanByJenisPelayanan($jenisPelayanan, $bulan, $tahun);

    if ($pemeriksaans->isEmpty()) {
        $namaBulan = Carbon::createFromFormat('Y-m', $request->input('bulan'))->translatedFormat('F Y');
        return back()->with('error', "Data pemeriksaan untuk bulan $namaBulan dan pelayanan $jenisPelayanan tidak ditemukan.");
    }
     $namaBulan = Carbon::createFromFormat('Y-m', $request->input('bulan'))->translatedFormat('F Y');

    // Tentukan view berdasarkan jenis pelayanan
    switch (strtolower($jenisPelayanan)) {
        case 'umum':
            $view = 'pages.export.preview.umum';
            break;
        case 'kb':
            $view = 'pages.export.preview.kb';
            break;
        case 'kia_ibu_hamil':
            $view = 'pages.export.preview.kia_ibu_hamil';
            break;
        case 'kia_anak':
            $view = 'pages.export.preview.kia_anak';
            break;
        case 'ibu_nifas':
            $view = 'pages.export.preview.ibu_nifas';
            break;
        default:
            $view = 'pages.export.preview.pemeriksaan'; // fallback default
            break;
    }

    return view($view, compact('pemeriksaans', 'bulan', 'tahun', 'jenisPelayanan','namaBulan'));
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
    ]);

    [$tahun, $bulan] = explode('-', $request->input('bulan'));

    // Ambil semua pembayaran bulan-tahun tsb
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

    if ($pembayarans->isEmpty()) {
        return back()->with('error', 'Data pembayaran tidak ditemukan untuk bulan yang dipilih.');
    }

    // Kelompokkan data berdasarkan nama_pelayanan (untuk dipisah di tabel nanti)
    $pembayaransByPelayanan = $pembayarans->groupBy(function ($item) {
        return optional($item->pemeriksaanable->pendaftaran->pelayanan)->nama_pelayanan ?? 'Tidak Diketahui';
    });

    Carbon::setLocale('id');
    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    $pdf = Pdf::loadView('pages.export.laporan_pembayaran', compact(
        'pembayaransByPelayanan',
        'bulan',
        'tahun',
        'namaBulanTahun'
    ))->setPaper('a4', 'landscape');

    return $pdf->stream("laporan_pembayaran_{$namaBulanTahun}.pdf");
}


private function getPemeriksaanByJenisPelayanan($jenisPelayanan, $bulan, $tahun)
{
    switch ($jenisPelayanan) {
        case 'umum':
            return PemeriksaanUmum::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        case 'kia_ibu_hamil':
            return PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        case 'kia_anak':
            return PemeriksaanKiaAnak::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        case 'ibu_nifas':
            return PemeriksaanIbuNifas::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        case 'kb':
            return PemeriksaanKb::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        default:
            return collect(); // Jika jenis tidak dikenali, kembalikan koleksi kosong
    }
}




//preview pembayaran
public function previewPembayaran(Request $request)
{
    [$tahun, $bulan] = explode('-', $request->input('bulan'));

    // Ambil semua pembayaran di bulan & tahun tertentu
    $pembayarans = Pembayaran::with([
        'pemeriksaanable.pendaftaran.pasien',
        'pemeriksaanable.pendaftaran.bidan',
        'pemeriksaanable.pendaftaran.pelayanan',
        'pemeriksaanable.obat'
    ])
    ->whereMonth('created_at', $bulan)
    ->whereYear('created_at', $tahun)
    ->get();

    if ($pembayarans->isEmpty()) {
        $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)
            ->locale('id')->translatedFormat('F Y');
        return back()->with('error', "Data pembayaran untuk bulan $namaBulan tidak ditemukan.");
    }

    // Urutan jenis pelayanan
    $urutanPelayanan = [
        'Umum',
        'KIA Ibu Hamil',
        'KIA Anak',
        'Ibu Nifas',
        'KB',
    ];

    // Group berdasarkan nama pelayanan
    $pembayaransByPelayanan = $pembayarans
        ->groupBy(fn($p) => optional($p->pemeriksaanable->pendaftaran->pelayanan)->nama_pelayanan ?? 'Lainnya')
        ->sortBy(fn($group, $key) => array_search($key, $urutanPelayanan) !== false ? array_search($key, $urutanPelayanan) : 999);

    $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)->locale('id')->translatedFormat('F Y');

    return view('pages.export.preview.pembayaran', compact(
        'pembayaransByPelayanan',
        'bulan',
        'tahun',
        'namaBulan'
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
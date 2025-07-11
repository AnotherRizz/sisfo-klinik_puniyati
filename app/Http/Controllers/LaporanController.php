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
use Illuminate\Support\Str;


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
    $filterType = $request->input('filter_type');
    dd($filterType);

    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereDate('tgl_daftar', $tanggal)
            ->orderBy('tgl_daftar')
            ->get();

        $judul = 'Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        $filename = 'laporan_pendaftaran_' . Carbon::parse($tanggal)->format('Ymd') . '.pdf';

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereBetween('tgl_daftar', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tgl_daftar')
            ->get();

        $judul = 'Dari ' . $tanggalAwal->translatedFormat('d F Y') . ' - ' . $tanggalAkhir->translatedFormat('d F Y');
        $filename = 'laporan_pendaftaran_' . $tanggalAwal->format('Ymd') . '_' . $tanggalAkhir->format('Ymd') . '.pdf';

    } elseif ($filterType === 'bulan') {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereMonth('tgl_daftar', $bulan)
            ->whereYear('tgl_daftar', $tahun)
            ->orderBy('tgl_daftar')
            ->get();

        $namaBulan = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F Y');
        $judul = "Bulan $namaBulan";
        $filename = 'laporan_pendaftaran_' . Carbon::createFromDate($tahun, $bulan)->format('Ym') . '.pdf';

    } else {
        return redirect()->back()->with('error', 'Jenis filter tidak dikenali.');
    }

    if ($pendaftarans->isEmpty()) {
        return redirect()->back()->with('error', "Data pendaftaran untuk $judul tidak ditemukan.");
    }

    $pendaftaransByPelayanan = $pendaftarans->groupBy(fn($item) => $item->pelayanan->nama_pelayanan ?? 'Lainnya');

    $pdf = Pdf::loadView('pages.export.laporan_pendaftaran', [
        'pendaftaransByPelayanan' => $pendaftaransByPelayanan,
        'judul' => $judul,
        'allpendaftaran' => $pendaftarans,
    ])->setPaper('a4', 'landscape');

    return $pdf->download($filename);
}



//preview pendaftaran


public function previewPendaftaran(Request $request)
{
    $filterType = $request->input('filter_type');

    // Validasi sesuai tipe filter
    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereDate('tgl_daftar', $tanggal)
            ->orderBy('tgl_daftar')
            ->get();

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereBetween('tgl_daftar', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tgl_daftar')
            ->get();

    } elseif ($filterType === 'bulan') {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);

        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])
            ->whereMonth('tgl_daftar', $bulan)
            ->whereYear('tgl_daftar', $tahun)
            ->orderBy('tgl_daftar')
            ->get();

    } else {
        return redirect()->back()->with('error', 'Jenis filter tidak dikenali.');
    }

    if ($pendaftarans->isEmpty()) {
        return redirect()->back()->with('error', "Data pendaftaran tidak ditemukan."  );
    }

    // Kelompokkan berdasarkan jenis pelayanan
    $pendaftaransByPelayanan = $pendaftarans->groupBy(fn($item) => $item->pelayanan->nama_pelayanan ?? 'Lainnya');
    $allpendaftaran = $pendaftarans;
    return view('pages.export.preview.pendaftaran', [
        'pendaftaransByPelayanan' => $pendaftaransByPelayanan,
        'filterType' => $filterType,
        'request' => $request,
        'all' => $allpendaftaran
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
    $filterType = $request->input('filter_type');
    $jenisPelayanan = $request->input('jenis_pelayanan');
    $pemeriksaans = collect(); // default kosong
    $judul = '';
    $namaBulanTahun = '';

    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pelayanan' => 'required|string',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();
        $pemeriksaans = $this->getPemeriksaanTanggal($jenisPelayanan, $tanggal);

        $judul = 'Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'jenis_pelayanan' => 'required|string',
        ]);

        $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $pemeriksaans = $this->getPemeriksaanRentang($jenisPelayanan, $tanggalAwal, $tanggalAkhir);

        $judul = 'Rentang ' . $tanggalAwal->translatedFormat('d F Y') . ' - ' . $tanggalAkhir->translatedFormat('d F Y');

    } elseif ($filterType === 'bulan') {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'jenis_pelayanan' => 'required|string',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);
        $pemeriksaans = $this->getPemeriksaanBulanan($jenisPelayanan, $bulan, $tahun);

        $namaBulanTahun = Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F_Y');
        $judul = 'Bulan ' . Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F Y');

    } else {
        return back()->with('error', 'Jenis filter tidak valid.');
    }

    // Cek jika tidak ada data
    if (!$pemeriksaans instanceof \Illuminate\Support\Collection || $pemeriksaans->isEmpty()) {
        return back()->with('error', "Data pemeriksaan untuk $judul dan pelayanan $jenisPelayanan tidak ditemukan.");
    }

    $namaPelayanan = ucwords(str_replace('_', ' ', $jenisPelayanan));

    // Tentukan view berdasarkan jenis pelayanan
    switch (strtolower($jenisPelayanan)) {
        case 'umum':
            $view = 'pages.export.pdf.umum';
            break;
        case 'kb':
            $view = 'pages.export.pdf.kb';
            break;
        case 'kesehatan_ibu_hamil':
            $view = 'pages.export.pdf.kia_ibu_hamil';
            break;
        case 'kesehatan_anak':
            $view = 'pages.export.pdf.kia_anak';
            break;
        case 'ibu_nifas':
            $view = 'pages.export.pdf.ibu_nifas';
            break;
        default:
            $view = 'pages.export.pdf.default';
            break;
    }

    // Generate PDF
    $pdf = Pdf::loadView($view, [
        'pemeriksaans' => $pemeriksaans,
        'namaPelayanan' => $namaPelayanan,
        'judul' => $judul,
        'filterType' => $filterType,
    ])->setPaper('f4', 'landscape');

    return $pdf->stream("laporan_pemeriksaan_{$namaPelayanan}_" . Str::slug($judul) . ".pdf");
}




public function previewPemeriksaan(Request $request)
{
    $filterType = $request->input('filter_type');
    $jenisPelayanan = $request->input('jenis_pelayanan');
    $pemeriksaans = collect(); // default kosong
    $judul = '';
    $namaBulan = '';

    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pelayanan' => 'required|string'
        ]);

        $tanggal = Carbon::parse($request->input('tanggal'))->toDateString();
        $pemeriksaans = $this->getPemeriksaanTanggal($jenisPelayanan, $tanggal);

        $judul = 'Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'jenis_pelayanan' => 'required|string'
        ]);

        $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $pemeriksaans = $this->getPemeriksaanRentang($jenisPelayanan, $tanggalAwal, $tanggalAkhir);

        $judul = 'Rentang ' . $tanggalAwal->translatedFormat('d F Y') . ' - ' . $tanggalAkhir->translatedFormat('d F Y');

  } elseif ($filterType === 'bulan') {
    $request->validate([
        'bulan' => 'required|date_format:Y-m',
        'jenis_pelayanan' => 'required|string'
    ]);

    [$tahun, $bulan] = explode('-', $request->bulan);
    $pemeriksaans = $this->getPemeriksaanBulanan($jenisPelayanan, $bulan, $tahun); 

    $namaBulan = Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F Y');
    $judul = 'Bulan ' . $namaBulan;


    } else {
        return back()->with('error', 'Jenis filter tidak valid.');
    }

    if ($pemeriksaans->isEmpty()) {
        return back()->with('error', "Data pemeriksaan untuk $judul dan pelayanan $jenisPelayanan tidak ditemukan.");
    }

    // Tentukan view berdasarkan jenis pelayanan
    switch (strtolower($jenisPelayanan)) {
        case 'umum':
            $view = 'pages.export.preview.umum';
            break;
        case 'kb':
            $view = 'pages.export.preview.kb';
            break;
        case 'kesehatan_ibu_hamil':
            $view = 'pages.export.preview.kia_ibu_hamil';
            break;
        case 'kesehatan_anak':
            $view = 'pages.export.preview.kia_anak';
            break;
        case 'ibu_nifas':
            $view = 'pages.export.preview.ibu_nifas';
            break;
        default:
            $view = 'pages.export.preview.pemeriksaan';
            break;
    }

    return view($view, [
        'pemeriksaans' => $pemeriksaans,
        'jenisPelayanan' => $jenisPelayanan,
        'judul' => $judul,
        'filterType' => $filterType,
        'namaBulan' => $namaBulan,
        'request' => $request,
    ]);
}

protected function getModelByJenisPelayanan($jenis)
{
    $map = [
        'umum' => \App\Models\PemeriksaanUmum::class,
        'kb' => \App\Models\PemeriksaanKb::class,
        'kesehatan_ibu_hamil' => \App\Models\PemeriksaanKiaIbuHamil::class,
        'kesehatan_anak' => \App\Models\PemeriksaanKiaAnak::class,
        'ibu_nifas' => \App\Models\PemeriksaanIbuNifas::class,
    ];

    return $map[strtolower($jenis)] ?? null;
}

protected function getPemeriksaanTanggal($jenis, $tanggal)
{
    $model = $this->getModelByJenisPelayanan($jenis);

    if (!$model) return collect(); // BUKAN null

    return $model::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan'])
        ->whereDate('created_at', $tanggal)
        ->get();
}


protected function getPemeriksaanRentang($jenis, $awal, $akhir)
{
    $model = $this->getModelByJenisPelayanan($jenis);

    if (!$model) return collect();

    return $model::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan']) 
        ->whereBetween('created_at', [$awal, $akhir])
        ->get(); // ✅ TAMBAHKAN return
}


protected function getPemeriksaanBulanan($jenis, $bulan, $tahun)
{
    $model = $this->getModelByJenisPelayanan($jenis);

    if (!$model) return collect();

    return $model::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan']) 
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->get(); // ✅ return
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
    $filterType = $request->input('filter_type');
    $pembayarans = collect(); // default kosong
    $judul = '';
    $namaBulan = '';

    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = \Carbon\Carbon::parse($request->tanggal)->toDateString();
        $judul = 'Tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereDate('tgl_bayar', $tanggal)
            ->orderBy('tgl_bayar')
            ->get();

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $awal = \Carbon\Carbon::parse($request->tanggal_awal)->startOfDay();
        $akhir = \Carbon\Carbon::parse($request->tanggal_akhir)->endOfDay();
        $judul = 'Rentang ' . $awal->translatedFormat('d F Y') . ' - ' . $akhir->translatedFormat('d F Y');

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereBetween('tgl_bayar', [$awal, $akhir])
            ->orderBy('tgl_bayar')
            ->get();

    } elseif ($filterType === 'bulan') {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);
        $judul = 'Bulan ' . \Carbon\Carbon::createFromDate($tahun, $bulan)->translatedFormat('F Y');

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->orderBy('tgl_bayar')
            ->get();

    } else {
        return back()->with('error', 'Jenis filter tidak valid.');
    }

    if ($pembayarans->isEmpty()) {
        return back()->with('error', "Data pembayaran untuk $judul tidak ditemukan.");
    }

    // Group by nama pelayanan
    $pembayaransByPelayanan = $pembayarans->groupBy(function ($item) {
        return optional($item->pemeriksaanable->pendaftaran->pelayanan)->nama_pelayanan ?? 'Lainnya';
    });

    $namaBulanTahun = now()->translatedFormat('d_F_Y');

    $pdf = Pdf::loadView('pages.export.laporan_pembayaran', compact(
        'pembayaransByPelayanan',
        'judul',
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

        case 'kesehatan_ibu_hamil':
            return PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

        case 'kesehatan_anak':
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
    $filterType = $request->input('filter_type');
    $judul = '';
    $namaBulan = '';
    $pembayarans = collect(); // default kosong

    if ($filterType === 'hari') {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = \Carbon\Carbon::parse($request->tanggal)->toDateString();

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereDate('tgl_bayar', $tanggal)
            ->orderBy('tgl_bayar')
            ->get();

        $judul = 'Tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');

    } elseif ($filterType === 'rentang') {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = \Carbon\Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggalAkhir = \Carbon\Carbon::parse($request->tanggal_akhir)->endOfDay();

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tgl_bayar')
            ->get();

        $judul = 'Rentang ' . $tanggalAwal->translatedFormat('d F Y') . ' - ' . $tanggalAkhir->translatedFormat('d F Y');

    } elseif ($filterType === 'bulan') {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);

        $pembayarans = Pembayaran::with(['pemeriksaanable.pendaftaran.pasien'])
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->orderBy('tgl_bayar')
            ->get();

        $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F Y');
        $judul = 'Bulan ' . $namaBulan;

    } else {
        return back()->with('error', 'Jenis filter tidak valid.');
    }

    if ($pembayarans->isEmpty()) {
        return back()->with('error', "Data pembayaran untuk $judul tidak ditemukan.");
    }
    $pembayaransByPelayanan = $pembayarans->groupBy(function ($item) {
    return optional($item->pemeriksaanable->pendaftaran->pelayanan)->nama_pelayanan ?? 'Lainnya';
});


    return view('pages.export.preview.pembayaran', [
        'pembayaransByPelayanan' => $pembayaransByPelayanan,
        'judul' => $judul,
        'namaBulan' => $namaBulan,
        'filterType' => $filterType,
        'request' => $request,
    ]);
}




public function export(Request $request)
    {
        $nama_pelayanan = $request->nama_pelayanan;

        $pemeriksaan = match ($nama_pelayanan) {
            'Umum' => PemeriksaanUmum::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'KB' => PemeriksaanKb::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'Kesehatan Ibu Hamil' => PemeriksaanKiaIbuHamil::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'Ibu Nifas' => PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            'Kesehatan Anak' => PemeriksaanKiaAnak::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obat'])->get(),
            default => collect(),
        };

        $pdf = Pdf::loadView('pages.export.pemeriksaan.all', compact('pemeriksaan', 'nama_pelayanan'))->setPaper('A4', 'landscape');

        return $pdf->download('laporan-pemeriksaan-'.$nama_pelayanan.'.pdf');
    }









}
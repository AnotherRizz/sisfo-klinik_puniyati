<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
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

// export pemeriksaan berdasarkan pelayanan
public function export(Request $request)
{
    // Ambil parameter nama_pelayanan dari request
    $namaPelayanan = $request->get('nama_pelayanan', 'Umum'); // Default: 'Umum'

    // Query data pemeriksaan berdasarkan nama_pelayanan
    $pemeriksaan = Pemeriksaan::with([
        'pendaftaran.pasien',
        'pendaftaran.bidan',
        'pendaftaran.pelayanan',
        'obat'
    ])
    ->whereHas('pendaftaran.pelayanan', function ($query) use ($namaPelayanan) {
        $query->where('nama_pelayanan', $namaPelayanan);
    })
    ->get();

    // Generate PDF
    $pdf = Pdf::loadView('pages.export.pemeriksaan.all', compact('pemeriksaan', 'namaPelayanan'))
              ->setPaper('A4', 'landscape');

    return $pdf->stream("data_pemeriksaan_{$namaPelayanan}.pdf");
}




    /**
     * Export laporan pemeriksaan to PDF.
     */
 public function exportPemeriksaan(Request $request)
{
    $bulanInput = $request->input('bulan'); // ex: "2025-06"
    [$tahun, $bulan] = explode('-', $bulanInput);
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pemeriksaans = Pemeriksaan::with([
            'pendaftaran.pasien',
            'pendaftaran.bidan',
            'pendaftaran.pelayanan',
            'obat'
        ])
        ->whereHas('pendaftaran', function ($query) use ($jenisPelayanan) {
            $query->where('pelayanan_id', $jenisPelayanan);
        })
        ->whereMonth('tgl_kembali', $bulan)
        ->whereYear('tgl_kembali', $tahun)
        ->orderBy('tgl_kembali')
        ->get();

    if ($pemeriksaans->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk bulan dan jenis pelayanan yang dipilih.');
    }

    $namaPelayanan = optional($pemeriksaans->first()->pendaftaran->pelayanan)->nama_pelayanan ?? '-';

    $pdf = Pdf::loadView('pages.export.laporan_pemeriksaan', compact(
        'pemeriksaans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ))
     ->setPaper('a4', 'landscape');

     $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

   return $pdf->download("laporan_pemeriksaan_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}

public function previewPemeriksaan(Request $request)
{
    $bulanInput = $request->input('bulan'); // ex: "2025-06"
    [$tahun, $bulan] = explode('-', $bulanInput);
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pemeriksaans = Pemeriksaan::with([
            'pendaftaran.pasien',
            'pendaftaran.bidan',
            'pendaftaran.pelayanan',
            'obat'
        ])
        ->whereHas('pendaftaran', function ($query) use ($jenisPelayanan) {
            $query->where('pelayanan_id', $jenisPelayanan);
        })
        ->whereMonth('tgl_kembali', $bulan)
        ->whereYear('tgl_kembali', $tahun)
        ->orderBy('tgl_kembali')
        ->get();

    // Ambil nama pelayanan dari ID
    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';

    if ($pemeriksaans->isEmpty()) {
        $namaBulan = Carbon::createFromFormat('Y-m', $bulanInput)->locale('id')->translatedFormat('F Y');

        return back()->with('error', "Data pemeriksaan untuk bulan $namaBulan dan pelayanan $namaPelayanan tidak ditemukan.");
    }

    return view('pages.export.preview.pemeriksaan', compact(
        'pemeriksaans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ));
}




    /**
     * Export laporan pembayaran to PDF.
     */
public function exportPembayaran(Request $request)
{
    // Validasi inputan
    $request->validate([
        'bulan' => 'required|date_format:Y-m',
        'jenis_pelayanan' => 'required|exists:pelayanan,id',
    ]);

    // Ambil data bulan dan tahun dari input
    $bulanInput = $request->input('bulan'); // Format "YYYY-MM"
    [$tahun, $bulan] = explode('-', $bulanInput);
    $jenisPelayanan = $request->input('jenis_pelayanan');

    // Query data pembayaran
    $pembayarans = Pembayaran::with([
            'pemeriksaan.pendaftaran.pasien',
            'pemeriksaan.pendaftaran.bidan',
            'pemeriksaan.pendaftaran.pelayanan',
            'pemeriksaan.obat'
        ])
        ->whereHas('pemeriksaan.pendaftaran', function ($query) use ($jenisPelayanan) {
            $query->where('pelayanan_id', $jenisPelayanan);
        })
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->orderBy('created_at')
        ->get();

    // Cek apakah data kosong
    if ($pembayarans->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk bulan dan jenis pelayanan yang dipilih.');
    }

    // Ambil nama pelayanan dari hasil query
    $namaPelayanan = optional($pembayarans->first()->pemeriksaan->pendaftaran->pelayanan)->nama_pelayanan ?? 'Umum';

    // Gunakan Carbon untuk menghasilkan nama bulan yang diterjemahkan
    Carbon::setLocale('id');
    $namaBulanTahun = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F_Y');

    // Buat PDF dengan data pembayaran
    $pdf = Pdf::loadView('pages.export.laporan_pembayaran', compact(
            'pembayarans',
            'bulan',
            'tahun',
            'namaPelayanan'
        ))
        ->setPaper('a4', 'landscape');

    // Unduh file dengan nama yang lebih informatif
    return $pdf->download("laporan_pembayaran_{$namaPelayanan}_{$namaBulanTahun}.pdf");
}


//preview pembayaran
public function previewpembayaran(Request $request)
{
    $bulanInput = $request->input('bulan'); // ex: "2025-06"
    [$tahun, $bulan] = explode('-', $bulanInput);
    $jenisPelayanan = $request->input('jenis_pelayanan');

    $pembayarans = Pembayaran::with([
        'pemeriksaan.pendaftaran.pasien',
        'pemeriksaan.pendaftaran.bidan',
        'pemeriksaan.pendaftaran.pelayanan',
        'pemeriksaan.obat' // Relasi many-to-many
    ])
    ->whereHas('pemeriksaan.pendaftaran', function ($query) use ($jenisPelayanan) {
        $query->where('pelayanan_id', $jenisPelayanan);
    })
    ->whereMonth('created_at', $bulan)
    ->whereYear('created_at', $tahun)
    ->orderBy('created_at')
    ->get();

    // Ambil nama pelayanan dari ID (walaupun data kosong)
    $namaPelayanan = Pelayanan::find($jenisPelayanan)?->nama_pelayanan ?? '-';

    if ($pembayarans->isEmpty()) {
        $namaBulan = Carbon::createFromFormat('Y-m', $bulanInput)->locale('id')->translatedFormat('F Y');
        return back()->with('error', "Data pembayaran untuk bulan $namaBulan dan pelayanan $namaPelayanan tidak ditemukan.");
    }

    return view('pages.export.preview.pembayaran', compact(
        'pembayarans',
        'bulan',
        'tahun',
        'namaPelayanan'
    ));
}











}
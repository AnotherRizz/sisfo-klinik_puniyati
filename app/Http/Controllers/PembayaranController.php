<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Obat;
use App\Models\PemeriksaanUmum;
use App\Models\PemeriksaanKb;
use App\Models\PemeriksaanKiaIbuHamil;
use App\Models\PemeriksaanKiaAnak;
use App\Models\PemeriksaanIbuNifas;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;


class PembayaranController extends Controller
{
public function index(Request $request)
{
    // ✅ Default 'filter_tanggal' jadi 'hari_ini'
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');
    $search = $request->get('search');

    // Gabungkan semua jenis pemeriksaan
    $all = collect()
        ->merge(PemeriksaanUmum::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pembayaran'])->get())
        ->merge(PemeriksaanKb::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pembayaran'])->get())
        ->merge(PemeriksaanKiaIbuHamil::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pembayaran'])->get())
        ->merge(PemeriksaanKiaAnak::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pembayaran'])->get())
        ->merge(PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'pendaftaran.bidan', 'pembayaran'])->get());

    // ✅ Filter berdasarkan tanggal (default hari ini)
    if ($filterTanggal === 'hari_ini') {
        $all = $all->filter(fn($item) => \Carbon\Carbon::parse($item->created_at)->isToday());
    }

    if ($search) {
    $searchLower = strtolower($search);
    $all = $all->filter(function ($item) use ($searchLower) {
        $pasien = $item->pendaftaran->pasien ?? null;

        // Konversi tanggal lahir ke format 'd F Y' dalam lowercase untuk dibandingkan
        $tglLahirFormatted = $pasien && $pasien->tgl_lahir
            ? strtolower(\Carbon\Carbon::parse($pasien->tgl_lahir)->translatedFormat('d F Y'))
            : '';

        // Format tambahan misal '13 juni'
        $tglLahirShort = $pasien && $pasien->tgl_lahir
            ? strtolower(\Carbon\Carbon::parse($pasien->tgl_lahir)->translatedFormat('d F'))
            : '';

        return $pasien &&
            (
                str_contains(strtolower($pasien->nama_pasien), $searchLower) ||
                str_contains(strtolower($pasien->no_rm), $searchLower) ||
                str_contains(strtolower($pasien->alamat), $searchLower) ||
                str_contains($tglLahirFormatted, $searchLower) ||
                str_contains($tglLahirShort, $searchLower)
            );
    });
}


    // Urutkan dan paginasi
    $all = $all->sortByDesc('created_at')->values();
    $perPage = $request->get('per_page', 10);
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();

    $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
        $all->slice(($currentPage - 1) * $perPage, $perPage),
        $all->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('pages.pembayaran.index', [
        'pembayarans' => $pagedData,
        'selectedTanggal' => $filterTanggal,
        'search' => $search,
    ]);
}




public function create(Request $request)
{
    $pemeriksaans = collect()
        ->merge(PemeriksaanUmum::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obatPemeriksaan.obat')
            ->whereDoesntHave('pembayaran')->get())
        ->merge(PemeriksaanKb::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obatPemeriksaan.obat')
            ->whereDoesntHave('pembayaran')->get())
        ->merge(PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obatPemeriksaan.obat')
            ->whereDoesntHave('pembayaran')->get())
        ->merge(PemeriksaanKiaAnak::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obatPemeriksaan.obat')
            ->whereDoesntHave('pembayaran')->get())
        ->merge(PemeriksaanIbuNifas::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan', 'obatPemeriksaan.obat')
            ->whereDoesntHave('pembayaran')->get())
        ->sortByDesc('created_at')
        ->values();

    return view('pages.pembayaran.create', [
        'pemeriksaans' => $pemeriksaans,
        'obats' => Obat::all(),
        'id'=>$request->id
    ]);
}



    public function store(Request $request)
    {
        $request->merge([
            'biaya_konsultasi' => str_replace('.', '', $request->biaya_konsultasi),
            'biaya_administrasi' => str_replace('.', '', $request->biaya_administrasi),
            'biaya_tindakan' => str_replace('.', '', $request->biaya_tindakan),
        ]);

        $data = $request->validate([
            'pemeriksaanable_id' => 'required|string',
            'pemeriksaanable_type' => 'required|string|in:PemeriksaanUmum,PemeriksaanKb,PemeriksaanKiaIbuHamil,PemeriksaanKiaAnak,PemeriksaanIbuNifas',
            'tgl_bayar' => 'required|date',
            'administrasi' => 'nullable|string',
            'biaya_administrasi' => 'nullable|numeric',
            'biaya_konsultasi' => 'nullable|numeric',
            'tindakan' => 'nullable|string',
            'biaya_tindakan' => 'nullable|numeric',
            'jenis_bayar' => 'required|in:Tunai,Transfer',
        ]);

        $fullType = 'App\\Models\\' . $data['pemeriksaanable_type'];
            $pemeriksaan = $fullType::where('nomor_periksa', $data['pemeriksaanable_id'])
            ->with('obatPemeriksaan.obat') // ini yang sesuai dengan create()
            ->firstOrFail();


        $data['pemeriksaanable_id'] = $pemeriksaan->id;
        $data['pemeriksaanable_type'] = $fullType;

      foreach ($pemeriksaan->obatPemeriksaan as $pivotObat) {
            $obat = $pivotObat->obat;
            $jumlahDipakai = $pivotObat->jumlah_obat ?? 0;

            if (!$obat) continue; // abaikan jika tidak ada relasi obat

            if ($obat->stok_obat < $jumlahDipakai) {
                return redirect()->back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok_obat}, dibutuhkan: $jumlahDipakai");
            }

            $obat->decrement('stok_obat', $jumlahDipakai);
        }



        $last = Pembayaran::orderBy('kd_bayar', 'desc')->first();
        $nextNumber = $last && preg_match('/TRX(\d+)/', $last->kd_bayar, $matches) ? ((int)$matches[1] + 1) : 1;
        $data['kd_bayar'] = 'TRX' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        $pembayaran = Pembayaran::create($data);

        return redirect()->route('pembayaran.show', $pembayaran->id)
                         ->with('success', 'Pembayaran berhasil disimpan.');
    }

public function show(Pembayaran $pembayaran)
{
   $pembayaran->loadMorph('pemeriksaanable', [
        \App\Models\PemeriksaanUmum::class => ['obat'],
        \App\Models\PemeriksaanKb::class => ['obat'],
        \App\Models\PemeriksaanKiaIbuHamil::class => ['obat'],
        \App\Models\PemeriksaanKiaAnak::class => ['obat'],
        \App\Models\PemeriksaanIbuNifas::class => ['obat'],
    ]);

    $pemeriksaan = $pembayaran->pemeriksaanable;
    return view('pages.pembayaran.detail', compact('pembayaran', 'pemeriksaan'));
}







    


    public function export()
{
    $pembayaran = Pembayaran::with([
        'pemeriksaanable.pendaftaran.pasien',
        'pemeriksaanable.obatPemeriksaan.obat'
    ])->get();

    // Jika ingin mengelompokkan berdasarkan jenis pelayanan
    $pembayaransByPelayanan = $pembayaran->groupBy(function ($item) {
        $class = class_basename($item->pemeriksaanable_type);
        return match ($class) {
            'PemeriksaanUmum' => 'Umum',
            'PemeriksaanKiaIbuHamil' => 'Kesehatan Ibu Hamil',
            'PemeriksaanKiaAnak' => 'Kesehatan Anak',
            'PemeriksaanIbuNifas' => 'Ibu Nifas',
            'PemeriksaanKb' => 'KB',
            default => 'Lainnya',
        };
    });

    $pdf = Pdf::loadView('pages.export.pembayaran', compact('pembayaran', 'pembayaransByPelayanan'))
        ->setPaper('A4', 'landscape');
    return $pdf->stream('data_pembayaran.pdf');
}


   public function bukti($id)
{
    $pembayaran = Pembayaran::with([
        'pemeriksaanable.pendaftaran.pasien',
        'pemeriksaanable.pendaftaran.bidan',
        'pemeriksaanable.obatPemeriksaan.obat'
    ])->findOrFail($id);

    $pdf = Pdf::loadView('pages.export.bukti-bayar', compact('pembayaran'))->setPaper('A5', 'portrait');
    return $pdf->stream('bukti_pembayaran.pdf');
}


    public function edit($id)
{
   $pembayarans = Pembayaran::with([
    'pemeriksaanable.pendaftaran.pasien',
    'pemeriksaanable.pendaftaran.bidan',
    'pemeriksaanable.pendaftaran.pelayanan',
    'pemeriksaanable.obat'
])->findOrFail($id);


    // Ambil semua pemeriksaan dari berbagai jenis
   $pemeriksaans = collect()
    ->merge(PemeriksaanUmum::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')->get()->each(fn($p) => $p->obat))
    ->merge(PemeriksaanKb::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')->get()->each(fn($p) => $p->obat))
    ->merge(PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')->get()->each(fn($p) => $p->obat))
    ->merge(PemeriksaanKiaAnak::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')->get()->each(fn($p) => $p->obat))
    ->merge(PemeriksaanIbuNifas::with('pendaftaran.pasien', 'pendaftaran.bidan', 'pendaftaran.pelayanan')->get()->each(fn($p) => $p->obat))
    ->sortByDesc('created_at')
    ->values();


    return view('pages.pembayaran.edit', compact('pembayarans', 'pemeriksaans'));
}


  public function update(Request $request, $id)
{
    // Bersihkan format biaya dari tanda titik
    $request->merge([
        'biaya_konsultasi' => str_replace('.', '', $request->biaya_konsultasi),
        'biaya_administrasi' => str_replace('.', '', $request->biaya_administrasi),
        'biaya_tindakan' => str_replace('.', '', $request->biaya_tindakan),
    ]);

    // Validasi request
    $validated = $request->validate([
        'pemeriksaanable_type' => 'required|string|in:PemeriksaanUmum,PemeriksaanKb,PemeriksaanKiaIbuHamil,PemeriksaanKiaAnak,PemeriksaanIbuNifas',
        'pemeriksaanable_id' => 'required|integer',
 // akan diperbaiki dinamis di bawah
        'tgl_bayar' => 'required|date',
        'administrasi' => 'nullable|string',
        'biaya_administrasi' => 'nullable|numeric',
        'biaya_konsultasi' => 'nullable|numeric',
        'tindakan' => 'nullable|string',
        'biaya_tindakan' => 'nullable|numeric',
        'jenis_bayar' => 'required|in:Tunai,Transfer',
    ]);

    // Ambil data pembayaran lama
    $pembayaran = Pembayaran::with('pemeriksaanable.obat')->findOrFail($id);

    // Kembalikan stok obat dari pemeriksaan sebelumnya
    foreach ($pembayaran->pemeriksaanable->obat as $obat) {
        $obat->increment('stok_obat');
    }

   $fullType = 'App\\Models\\' . $validated['pemeriksaanable_type'];

if (!class_exists($fullType)) {
    return redirect()->back()->with('error', 'Jenis pemeriksaan tidak dikenali.');
}

$newPemeriksaan = $fullType::with('obat')->find($validated['pemeriksaanable_id']);

if (!$newPemeriksaan) {
    return redirect()->back()->with('error', 'Data pemeriksaan tidak ditemukan.');
}


    // Cek stok obat untuk pemeriksaan baru
    foreach ($newPemeriksaan->obat as $obat) {
        if ($obat->stok_obat < 1) {
            return redirect()->back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi.");
        }
    }

    // Kurangi stok obat berdasarkan pemeriksaan baru
    foreach ($newPemeriksaan->obat as $obat) {
        $obat->decrement('stok_obat');
    }

    // Update data pembayaran
    $pembayaran->update([
        'pemeriksaanable_type' => $fullType,
        'pemeriksaanable_id' => $newPemeriksaan->id,
        'tgl_bayar' => $validated['tgl_bayar'],
        'administrasi' => $validated['administrasi'],
        'biaya_administrasi' => $validated['biaya_administrasi'],
        'biaya_konsultasi' => $validated['biaya_konsultasi'],
        'tindakan' => $validated['tindakan'],
        'biaya_tindakan' => $validated['biaya_tindakan'],
        'jenis_bayar' => $validated['jenis_bayar'],
    ]);

    return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil diperbarui.');
}


    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
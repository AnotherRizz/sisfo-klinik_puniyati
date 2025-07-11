<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKiaIbuHamil;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KiaIbuHamilController extends Controller
{


public function index(Request $request)
{
    // Set default filter_tanggal ke "hari_ini" jika tidak ada di request
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');

    // Query untuk pemeriksaan umum
    $query = PemeriksaanKiaIbuHamil::with([
        'pendaftaran.pasien',
        'pendaftaran.pelayanan',
    ]);

    $pendaftaranBelumDiperiksa = Pendaftaran::with(['pasien', 'pelayanan'])
        ->where('pelayanan_id', 2)
        ->whereDoesntHave('PemeriksaanKiaIbuHamil');

    // Filter berdasarkan tanggal
    if ($filterTanggal === 'hari_ini') {
        $query->whereHas('pendaftaran', function ($q) {
            $q->whereDate('tgl_daftar', now()->toDateString());
        });

        $pendaftaranBelumDiperiksa->whereDate('tgl_daftar', now()->toDateString());
    }

    // Filter pencarian
    if ($search = $request->get('search')) {
         $query->whereHas('pendaftaran.pasien', function ($q) use ($search) {
            $q->where('nama_pasien', 'like', '%' . $search . '%')
              ->orWhere('no_rm', 'like', '%' . $search . '%')
              ->orWhere('alamat', 'like', '%' . $search . '%');
        });

        $pendaftaranBelumDiperiksa->whereHas('pasien', function ($q) use ($search) {
            $q->where('nama_pasien', 'like', '%' . $search . '%')
              ->orWhere('no_rm', 'like', '%' . $search . '%')
              ->orWhere('alamat', 'like', '%' . $search . '%');
        });
    }

    // Gabungkan hasil
    $PemeriksaanKiaIbuHamil = $query->get();
    $belumDiperiksa = $pendaftaranBelumDiperiksa->get();
    $dataGabungan = $PemeriksaanKiaIbuHamil->merge($belumDiperiksa);

    // Pagination manual
    $perPage = $request->get('per_page', 5);
    $currentPage = $request->get('page', 1);
    $data = $dataGabungan->forPage($currentPage, $perPage);
    $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
        $data,
        $dataGabungan->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('pages.pemeriksaan.kia-ibu-hamil.index', compact('paginatedData', 'filterTanggal', 'search'));
}

public function create(Request $request)
    {
        return view('pages.pemeriksaan.kia-ibu-hamil.create', [
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKiaIbuHamil')
                ->whereHas('pelayanan', fn ($q) => $q->where('nama_pelayanan', 'Kesehatan Ibu Hamil'))
                ->with(['pasien', 'bidan', 'pelayanan'])->get(),
            'obats' => Obat::all(),
            'pendaftaran_id' => $request->pendaftaran_id,    
        ]);
    }

 public function store(Request $request)
{
    $request->validate([
        'pendaftaran_id' => 'required|exists:pendaftaran,id',
        'keluhan' => 'required|string',
        'riw_penyakit' => 'required|string',
        'td' => 'nullable|string',
        'bb' => 'nullable|numeric',
        'tb' => 'nullable|numeric',
        'suhu' => 'nullable|numeric',
        'saturasiOx' => 'nullable|numeric',
        'nadi' => 'required|numeric',
        'lila' => 'required|numeric',
        'hpht' => 'required|date',
        'hpl' => 'required|date',
        'gpa' => 'nullable|string',
        'riwayatkehamilankesehatan' => 'nullable|string',
        'umr_hamil' => 'required',
        'tifu' => 'required|numeric',
        'djj' => 'required|numeric',
        'ltkjanin' => 'required',
        'ktrkuterus' => 'required',
        'refla' => 'required',
        'lab' => 'required',
        'resti' => 'required',
        'riwayat_TT' => 'required',
        'tablet_tambah_darah' => 'required',
        'vitamin_mineral' => 'required',
        'asam_folat' => 'required',
        'diagnosa' => 'required|string',
        'intervensi' => 'required|string',
        'tindak_lnjt' => 'required|string',
        'tgl_kembali' => 'nullable|date',

        // Ini diubah ke nullable
        'obat_id' => 'nullable|array',
    'obat_id.*' => 'nullable|exists:obat,id',
    'dosis_carkai' => 'nullable|array',
    ]);

    // Generate nomor periksa otomatis
    $last = PemeriksaanKiaIbuHamil::latest()->first();
    $request['nomor_periksa'] = 'KH' . str_pad(($last->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);

    // Buat pemeriksaan tanpa data obat
    $pemeriksaan = PemeriksaanKiaIbuHamil::create($request->except('obat_id', 'dosis_carkai'));

    // Tambahkan data obat hanya jika ada input
   if ($request->filled('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Cek jika nilai obat valid dan bukan null atau kosong
        if (!empty($obat_id)) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obat_id,
                'dosis_carkai' => $request->dosis_carkai[$i] ?? null,
                'jumlah_obat' => $request->jumlah_obat[$i] ?? null,
            ]);
        }
    }
}


    return redirect()->route('kia-ibu-hamil.index')->with('success', 'Berhasil menyimpan data Kesehatan Ibu Hamil.');
}



    public function show($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        return view('pages.pemeriksaan.kia-ibu-hamil.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        $pdf = Pdf::loadView('pages.export.resume.kia-ibu-hamil', compact('pemeriksaan'))->setPaper('A5', 'portrait');
        return $pdf->stream('resume_kia_ibu.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with('pasien', 'pelayanan')->get();
        $obats = Obat::all();
        return view('pages.pemeriksaan.kia-ibu-hamil.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
         'pendaftaran_id' => 'required|exists:pendaftaran,id',
        'keluhan' => 'required|string',
        'riw_penyakit' => 'required|string',
        'td' => 'nullable|string',
        'bb' => 'nullable|numeric',
        'tb' => 'nullable|numeric',
        'suhu' => 'nullable|numeric',
        'saturasiOx' => 'nullable|numeric',
        'nadi' => 'required|numeric',
        'lila' => 'required|numeric',
        'hpht' => 'required|date',
        'hpl' => 'required|date',
        'gpa' => 'nullable|string',
        'riwayatkehamilankesehatan' => 'nullable|string',
        'umr_hamil' => 'required',
        'tifu' => 'required|numeric',
        'djj' => 'required|numeric',
        'ltkjanin' => 'required',
        'ktrkuterus' => 'required',
        'refla' => 'required',
        'lab' => 'required',
        'resti' => 'required',
        'riwayat_TT' => 'required',
        'tablet_tambah_darah' => 'required',
        'vitamin_mineral' => 'required',
        'asam_folat' => 'required',
        'diagnosa' => 'required|string',
        'intervensi' => 'required|string',
        'tindak_lnjt' => 'required|string',
        'tgl_kembali' => 'nullable|date',

        // Ini diubah ke nullable
        'obat_id' => 'nullable|array',
    'obat_id.*' => 'nullable|exists:obat,id',
    'dosis_carkai' => 'nullable|array',
    ]);

    $pemeriksaan = PemeriksaanKiaIbuHamil::findOrFail($id);
    $pemeriksaan->update($request->except('obat_id', 'dosis_carkai'));

    // Hapus data obat lama
    $pemeriksaan->obatPemeriksaan()->delete();

    // Tambahkan data obat baru jika ada
   if ($request->filled('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Cek jika nilai obat valid dan bukan null atau kosong
        if (!empty($obat_id)) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obat_id,
                'dosis_carkai' => $request->dosis_carkai[$i] ?? null,
                'jumlah_obat' => $request->jumlah_obat[$i] ?? null,
            ]);
        }
    }
}


    return redirect()->route('kia-ibu-hamil.index')->with('success', 'Berhasil memperbarui data Kesehatan Ibu Hamil.');
}


    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kia-ibu.index')->with('success', 'Data berhasil dihapus.');
    }
}
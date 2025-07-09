<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Pelayanan;
use App\Models\PemeriksaanUmum;
use App\Models\ObatPemeriksaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
 
class PasienUmumController extends Controller
{
public function index(Request $request)
{
    // Set default filter_tanggal ke "hari_ini" jika tidak ada di request
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');

    // Query untuk pemeriksaan umum
    $query = PemeriksaanUmum::with([
        'pendaftaran.pasien',
        'pendaftaran.pelayanan',
    ]);

    $pendaftaranBelumDiperiksa = Pendaftaran::with(['pasien', 'pelayanan'])
        ->where('pelayanan_id', 1)
        ->whereDoesntHave('pemeriksaanUmum');

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
              ->orWhere('no_rm', 'like', '%' . $search . '%');
        });

        $pendaftaranBelumDiperiksa->whereHas('pasien', function ($q) use ($search) {
            $q->where('nama_pasien', 'like', '%' . $search . '%')
              ->orWhere('no_rm', 'like', '%' . $search . '%');
        });
    }

    // Gabungkan hasil
    $pemeriksaanUmum = $query->get();
    $belumDiperiksa = $pendaftaranBelumDiperiksa->get();
    $dataGabungan = $pemeriksaanUmum->merge($belumDiperiksa);

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

    return view('pages.pemeriksaan.umum.index', compact('paginatedData', 'filterTanggal', 'search'));
}




public function create(Request $request)
{

    // Debugging
 
    return view('pages.pemeriksaan.umum.create', [
        'pasiens' => Pasien::all(),
        'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanUmum')
            ->whereHas('pelayanan', function ($query) {
                $query->where('nama_pelayanan', 'Umum');
            })
            ->with(['pasien', 'bidan', 'pelayanan'])
            ->get(),
        'bidans' => Bidan::all(),
        'obats' => Obat::all(),
        'pendaftaran_id' => $request->pendaftaran_id, // Pastikan variabel ini diteruskan
    ]);
}



   public function store(Request $request)
{
    $validated = $request->validate([
        'pendaftaran_id' => 'required|exists:pendaftaran,id',
        // 'nomor_periksa' => 'required|string',
        'keluhan' => 'required|string',
        'pemeriksaan_penunjang' => 'required|string',
        'riw_penyakit' => 'required|string',
        'riw_alergi' => 'nullable|string',
        'td' => 'nullable',
        'bb' => 'nullable|numeric',
        'tb' => 'nullable|numeric',
        'suhu' => 'nullable|numeric',
        'saturasiOx' => 'nullable|numeric',
        'diagnosa' => 'required|string',
        'tindakan' => 'required|string',
        'tindak_lnjt' => 'required',
        'tgl_kembali' => 'nullable|date',
       // Ubah validasi obat jadi optional
        'obat_id' => 'nullable|array',
    'obat_id.*' => 'nullable|exists:obat,id',
    'dosis_carkai' => 'nullable|array',
    ]);

    // 🟢 Generate nomor_periksa
    $last = PemeriksaanUmum::latest('id')->first();
    $nextId = $last ? $last->id + 1 : 1;
    $nomor_periksa = 'UM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

    $data = $validated;
    $data['nomor_periksa'] = $nomor_periksa;

    $pemeriksaan = PemeriksaanUmum::create($data);

   if ($request->has('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Lewati jika tidak ada obat yang dipilih ("" atau null)
        if (empty($obat_id)) continue;

        $dosis = $request->dosis_carkai[$i] ?? null;

        $pemeriksaan->obatPemeriksaan()->create([
            'obat_id' => $obat_id,
            'dosis_carkai' => $dosis,
        ]);
    }
}


    return redirect()->route('umum.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
}


    public function show($id)
    {
        $pemeriksaan = PemeriksaanUmum::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);
        return view('pages.pemeriksaan.umum.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanUmum::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.export.resume.umum', compact('pemeriksaan'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('resume_pemeriksaan_umum.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanUmum::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
        $obats = Obat::all();

        return view('pages.pemeriksaan.umum.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'pemeriksaan_penunjang' => 'required|string',
            'riw_penyakit' => 'required|string',
            'riw_alergi' => 'nullable|string',
            'td' => 'nullable',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'saturasiOx' => 'nullable|numeric',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'nullable|date',
             // Ubah validasi obat jadi optional
        'obat_id' => 'nullable|array',
        'obat_id.*' => 'nullable|exists:obat,id',
        'dosis_carkai' => 'nullable|array',
        ]);

        $pemeriksaan = PemeriksaanUmum::findOrFail($id);
        $pemeriksaan->update($validated);

        // Hapus & buat ulang data obat pemeriksaan
        $pemeriksaan->obatPemeriksaan()->delete();

        // Tambahkan data obat baru jika ada
    if ($request->has('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Lewati jika tidak ada obat yang dipilih ("" atau null)
        if (empty($obat_id)) continue;

        $dosis = $request->dosis_carkai[$i] ?? null;

        $pemeriksaan->obatPemeriksaan()->create([
            'obat_id' => $obat_id,
            'dosis_carkai' => $dosis,
        ]);
    }
}


        return redirect()->route('umum.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanUmum::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('umum.index')->with('success', 'Data berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKb;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienKbController extends Controller
{

 public function index(Request $request)
{
    // Query untuk Pemeriksaan KB
    $query = PemeriksaanKb::with([
        'pendaftaran.pasien',
        'pendaftaran.pelayanan',
    ]);

    // Query untuk Pendaftaran yang belum diperiksa
    $pendaftaranBelumDiperiksa = Pendaftaran::with(['pasien', 'pelayanan'])
        ->where('pelayanan_id', 5) // id_pelayanan untuk KB
        ->whereDoesntHave('PemeriksaanKb');

    // ✅ Set default filter ke 'hari_ini' jika tidak ada
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');

    if ($filterTanggal === 'hari_ini') {
        $query->whereHas('pendaftaran', fn($q) =>
            $q->whereDate('tgl_daftar', now()->toDateString()));
        $pendaftaranBelumDiperiksa->whereDate('tgl_daftar', now()->toDateString());
    }

    // Pencarian
    if ($search = $request->get('search')) {
        $query->whereHas('pendaftaran.pasien', fn($q) =>
            $q->where('nama_pasien', 'like', '%' . $search . '%')
              ->orWhere('no_rm', 'like', '%' . $search . '%'));

        $pendaftaranBelumDiperiksa->whereHas('pasien', fn($q) =>
            $q->where('nama_pasien', 'like', '%' . $search . '%')
              ->orWhere('no_rm', 'like', '%' . $search . '%'));
    }

    // Gabungkan Data
    $PemeriksaanKb = $query->get();
    $belumDiperiksa = $pendaftaranBelumDiperiksa->get();
    $dataGabungan = $PemeriksaanKb->merge($belumDiperiksa);

    // Pagination Manual
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

    return view('pages.pemeriksaan.kb.index', compact('paginatedData', 'filterTanggal', 'search'));
}


public function create(Request $request) 
    {
        return view('pages.pemeriksaan.kb.create', [
            'pasiens' => Pasien::all(),
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKb')
                ->whereHas('pelayanan', function ($query) {
                    $query->where('nama_pelayanan', 'KB');
                })
                ->with(['pasien', 'bidan', 'pelayanan'])
                ->get(),
            'bidans' => Bidan::all(),
            'obats' => Obat::all(),
            'pendaftaran_id' => $request->pendaftaran_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
             'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'nullable',
            'bb' => 'nullable',
            'alergi' => 'required',
            'hpht' => 'nullable|date',
            'jmlhanak' => 'nullable|numeric',
            'tglpasang' => 'nullable|date',
            'metode_kb' => 'nullable|in:Pil,Suntik,Implan,IUD,Kondom',
            'edukasi' => 'nullable|string',
            'intervensi' => 'nullable|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'nullable|date',
        ]);

        // generate nomor_periksa otomatis
        $last = PemeriksaanKb::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $nomor_periksa = 'KB' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $data = $validated;
        $data['nomor_periksa'] = $nomor_periksa;

        $pemeriksaan = PemeriksaanKb::create($data);

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

        return redirect()->route('kb.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanKb::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);
        return view('pages.pemeriksaan.kb.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKb::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.export.resume.kb', compact('pemeriksaan'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('resume_pemeriksaan_kb.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKb::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
        $obats = Obat::all();

        return view('pages.pemeriksaan.kb.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'nullable',
            'bb' => 'nullable',
            'alergi' => 'required',
            'hpht' => 'nullable|date',
            'jmlhanak' => 'nullable|numeric',
            'tglpasang' => 'nullable|date',
            'metode_kb' => 'nullable|in:Pil,Suntik,Implan,IUD,Kondom',
            'edukasi' => 'nullable|string',
            'intervensi' => 'nullable|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'nullable|date',
           
        ]);

        $pemeriksaan = PemeriksaanKb::findOrFail($id);
        $pemeriksaan->update($validated);

        $pemeriksaan->obatPemeriksaan()->delete();
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

        return redirect()->route('kb.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKb::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kb.index')->with('success', 'Data berhasil dihapus.');
    }
}
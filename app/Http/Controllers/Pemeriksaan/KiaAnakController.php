<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKiaAnak;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KiaAnakController extends Controller
{

public function index(Request $request)
{
    // Set default filter_tanggal ke "hari_ini" jika tidak ada di request
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');

    // Query untuk pemeriksaan umum
    $query = PemeriksaanKiaAnak::with([
        'pendaftaran.pasien',
        'pendaftaran.pelayanan',
    ]);

    $pendaftaranBelumDiperiksa = Pendaftaran::with(['pasien', 'pelayanan'])
        ->where('pelayanan_id', 3)
        ->whereDoesntHave('PemeriksaanKiaAnak');

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
    $PemeriksaanKiaAnak = $query->get();
    $belumDiperiksa = $pendaftaranBelumDiperiksa->get();
    $dataGabungan = $PemeriksaanKiaAnak->merge($belumDiperiksa);

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

    return view('pages.pemeriksaan.kia-anak.index', compact('paginatedData', 'filterTanggal', 'search'));
}



public function create(Request $request)
    {
        return view('pages.pemeriksaan.kia-anak.create', [
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKiaAnak')
                ->whereHas('pelayanan', fn ($q) => $q->where('nama_pelayanan', 'Kesehatan Anak'))
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
            'riw_penyakit' => 'nullable|string',
            'riw_imunisasi' => 'nullable|string',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'pb' => 'required|numeric',
            'lk' => 'required|numeric',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'nullable|date',
           
        ]);

        $last = PemeriksaanKiaAnak::latest()->first();
        $request['nomor_periksa'] = 'KA' . str_pad(($last->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
        $pemeriksaan = PemeriksaanKiaAnak::create($request->except('obat_id', 'dosis_carkai'));

       if ($request->has('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Lewati jika tidak ada obat yang dipilih ("" atau null)
        if (empty($obat_id)) continue;

         $dosis = $request->dosis_carkai[$i] ?? null;
        $jumlah = $request->jumlah_obat[$i] ?? null;

        $pemeriksaan->obatPemeriksaan()->create([
            'obat_id' => $obat_id,
            'dosis_carkai' => $dosis,
            'jumlah_obat' => $jumlah,
        ]);
    }
}

        return redirect()->route('kia-anak.index')->with('success', 'Data Kesehatan Anak berhasil disimpan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        return view('pages.pemeriksaan.kia-anak.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        $pdf = Pdf::loadView('pages.export.resume.kia_anak', compact('pemeriksaan'))->setPaper('A5', 'portrait');
        return $pdf->stream('resume_kia_anak.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('obatPemeriksaan')->findOrFail($id);
        return view('pages.pemeriksaan.kia-anak.edit', [
            'pemeriksaan' => $pemeriksaan,
            'pendaftarans' => Pendaftaran::with('pasien')->get(),
            'obats' => Obat::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'nullable|string',
            'riw_imunisasi' => 'nullable|string',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'pb' => 'required|numeric',
            'lk' => 'required|numeric',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'nullable|date',
           
        ]);

        $pemeriksaan = PemeriksaanKiaAnak::findOrFail($id);
        $pemeriksaan->update($request->except('obat_id', 'dosis_carkai'));

        $pemeriksaan->obatPemeriksaan()->delete();
        if ($request->has('obat_id') && is_array($request->obat_id)) {
    foreach ($request->obat_id as $i => $obat_id) {
        // Lewati jika tidak ada obat yang dipilih ("" atau null)
        if (empty($obat_id)) continue;

         $dosis = $request->dosis_carkai[$i] ?? null;
        $jumlah = $request->jumlah_obat[$i] ?? null;

        $pemeriksaan->obatPemeriksaan()->create([
            'obat_id' => $obat_id,
            'dosis_carkai' => $dosis,
            'jumlah_obat' => $jumlah,
        ]);
    }
}

        return redirect()->route('kia-anak.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kia-anak.index')->with('success', 'Data berhasil dihapus.');
    }
}
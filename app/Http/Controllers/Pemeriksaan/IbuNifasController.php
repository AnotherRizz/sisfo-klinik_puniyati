<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanIbuNifas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IbuNifasController extends Controller
{
  
 public function index(Request $request)
{
    // Set default filter_tanggal ke "hari_ini" jika tidak ada di request
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');

    // Query untuk pemeriksaan umum
    $query = PemeriksaanIbuNifas::with([
        'pendaftaran.pasien',
        'pendaftaran.pelayanan',
    ]);

    $pendaftaranBelumDiperiksa = Pendaftaran::with(['pasien', 'pelayanan'])
        ->where('pelayanan_id', 4)
        ->whereDoesntHave('PemeriksaanIbuNifas');

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
    $PemeriksaanIbuNifas = $query->get();
    $belumDiperiksa = $pendaftaranBelumDiperiksa->get();
    $dataGabungan = $PemeriksaanIbuNifas->merge($belumDiperiksa);

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

    return view('pages.pemeriksaan.nifas.index', compact('paginatedData', 'filterTanggal', 'search'));
}

public function create(Request $request)
    {
        return view('pages.pemeriksaan.nifas.create', [
            'pasiens' => Pasien::all(),
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanIbuNifas')
                ->whereHas('pelayanan', function ($query) {
                    $query->where('nama_pelayanan', 'Ibu Nifas');
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
            'frek_kunjungan' => 'required',
            'td' => 'nullable',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'saturasiOx' => 'nullable|numeric',
            'diagnosa' => 'required|string',
            'alergi' => 'required|string',
            'tifu' => 'required',
            'lochea' => 'required',
            'payudara' => 'required',
            'lukajahit' => 'required',
            'tgllahir' => 'required|date',
            'tmptpersalinan' => 'required',
            'bantupersalinan' => 'required',
            'jnspersalinan' => 'required|string|in:Spontan,Cesar,Vacum',
            'infeksi_kompli' => 'required',
            'edukasi' => 'required',
            'intervensi' => 'required',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'nullable|date',
           
            // tambahkan field tambahan jika perlu
        ]);

        $last = PemeriksaanIbuNifas::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $nomor_periksa = 'NF' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $data = $validated;
        $data['nomor_periksa'] = $nomor_periksa;

        $pemeriksaan = PemeriksaanIbuNifas::create($data);

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

        return redirect()->route('nifas.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);
        return view('pages.pemeriksaan.nifas.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanIbuNifas::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.export.resume.nifas', compact('pemeriksaan'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('resume_pemeriksaan_ibu_nifas.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanIbuNifas::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
        $obats = Obat::all();

        return view('pages.pemeriksaan.nifas.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
          'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'frek_kunjungan' => 'required',
            'td' => 'nullable',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'saturasiOx' => 'nullable|numeric',
            'diagnosa' => 'required|string',
            'alergi' => 'required|string',
            'tifu' => 'required',
            'lochea' => 'required',
            'payudara' => 'required',
            'lukajahit' => 'required',
            'tgllahir' => 'required|date',
            'tmptpersalinan' => 'required',
            'bantupersalinan' => 'required',
            'jnspersalinan' => 'required|string|in:Spontan,Cesar,Vacum',
            'infeksi_kompli' => 'required',
            'edukasi' => 'required',
            'intervensi' => 'required',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'nullable|date',
           
        ]);

        $pemeriksaan = PemeriksaanIbuNifas::findOrFail($id);
        $pemeriksaan->update($validated);

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

        return redirect()->route('nifas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanIbuNifas::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('nifas.index')->with('success', 'Data berhasil dihapus.');
    }
}
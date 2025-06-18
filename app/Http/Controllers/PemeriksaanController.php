<?php

namespace App\Http\Controllers;

use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//    public function index()
//     {
//         $pemeriksaans = Pemeriksaan::with(['pasien', 'bidan', 'pelayanan', 'obat','pendaftaran'])->latest()->paginate(10);
//         return view('pages.pemeriksaan.index', compact('pemeriksaans'));
//     }
public function export()
{
    $pemeriksaan = Pemeriksaan::with(['pasien', 'bidan', 'pelayanan', 'obat','pendaftaran'])->get();

    $pdf = Pdf::loadView('pages.export.pemeriksaan', compact('pemeriksaan'));
return $pdf->stream('data_pemeriksaan_.pdf');

}
public function resume($id)
{
    $pemeriksaan = Pemeriksaan::with(['pasien', 'bidan', 'pelayanan', 'obat','pendaftaran'])
                    ->findOrFail($id);

    $pdf = Pdf::loadView('pages.export.resume', compact('pemeriksaan'))
              ->setPaper('A5', 'portrait'); // ukuran mirip struk

    return $pdf->stream('resume_pemeriksaan.pdf');
}

    
public function index(Request $request)
{
    $query = Pemeriksaan::with([
        'pendaftaran.pasien',
        'pendaftaran.obat',
        'pendaftaran.pelayanan',
        'bidan'
    ]);

    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('no_periksa', 'like', '%' . $search . '%')
              ->orWhereHas('pendaftaran.pasien', function ($pasienQuery) use ($search) {
                  $pasienQuery->where('nama_pasien', 'like', '%' . $search . '%');
              });
        });
    }

    $perPage = $request->get('per_page', 5);
    $pemeriksaans = $query->paginate($perPage);

    return view('pages.pemeriksaan.index', compact('pemeriksaans'));
}



    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    return view('pages.pemeriksaan.create', [
        'pasiens' => Pasien::all(),
        'pemeriksaans' => Pemeriksaan::all(),
        // Ambil pendaftaran yang belum punya relasi ke tabel pemeriksaan
        'pendaftarans' => Pendaftaran::doesntHave('pemeriksaan')->with(['pasien', 'bidan', 'pelayanan'])->get(),
        'bidans' => Bidan::all(),
        'obats' => Obat::all()
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate([
        'pendaftaran_id' => 'required|exists:pendaftaran,id',
        'obat_id' => 'required|array',
        'obat_id.*' => 'required|exists:obat,id',
        'dosis_carkai' => 'required|array',
        'dosis_carkai.*' => 'required|string',

        'keluhan' => 'required|string',
        'riw_penyakit' => 'required|string',
        'riw_imunisasi' => 'required|string',
        'td' => 'required|string',
        'bb' => 'required|numeric',
        'tb' => 'required|numeric',
        'suhu' => 'required|numeric',
        'saturasiOx' => 'required|numeric',
        'lila' => 'required|numeric',
        'pemeriksaan_ibu_hamil' => 'nullable|string',
        'pemeriksaan_ibu_nifas_kb' => 'nullable|string',
        'diagnosa' => 'required|string',
        'tindakan' => 'required|string',
        'tgl_kembali' => 'required|date',
    ]);

    $last = Pemeriksaan::latest('id')->first();
    $nextId = $last ? $last->id + 1 : 1;
    $no_periksa = 'PR' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

    $data = $request->except(['obat_id', 'dosis_carkai']);
    $data['no_periksa'] = $no_periksa;

    $pemeriksaan = Pemeriksaan::create($data);

    // Hubungkan dengan pivot table
    $pivot = [];
    foreach ($request->obat_id as $index => $obatId) {
        $pivot[$obatId] = ['dosis_carkai' => $request->dosis_carkai[$index]];
    }
    $pemeriksaan->obat()->attach($pivot);

    return redirect()->route('pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
}


    /**
     * Display the specified resource.
     */
   public function show(Pemeriksaan $pemeriksaan)
{
    $pemeriksaan->load('obat'); // Eager load relasi obat-obatan
    return view('pages.pemeriksaan.detail', compact('pemeriksaan'));
}



    /**
     * Show the form for editing the specified resource.
     */
public function edit(string $id)
{
    $pemeriksaan = Pemeriksaan::with('obat')->findOrFail($id); // muat relasi 'obat'
    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
    $obats = Obat::all(); // semua data obat untuk dropdown

    return view('pages.pemeriksaan.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
}




    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'no_periksa' => 'required',
        'pendaftaran_id' => 'required',
        'obat_id' => 'required|array',
         'obat_id.*' => 'required|exists:obat,id',
        'dosis_carkai' => 'required|array',
        'dosis_carkai.*' => 'required|string',
        'keluhan' => 'required',
        'riw_penyakit' => 'required',
        'riw_imunisasi' => 'required',
        'td' => 'required',
        'bb' => 'required',
        'tb' => 'required',
        'suhu' => 'required',
        'saturasiOx' => 'required',
        'lila' => 'required',
        'pemeriksaan_ibu_hamil' => 'required',
        'pemeriksaan_ibu_nifas_kb' => 'required',
        'diagnosa' => 'required',
        'tindakan' => 'required',
        'tgl_kembali' => 'required|date',
    ]);

    $pemeriksaan = Pemeriksaan::findOrFail($id);

    // Update data pemeriksaan utama
    $pemeriksaan->update([
        'no_periksa' => $validated['no_periksa'],
        'pendaftaran_id' => $validated['pendaftaran_id'],
        'keluhan' => $validated['keluhan'],
        'riw_penyakit' => $validated['riw_penyakit'],
        'riw_imunisasi' => $validated['riw_imunisasi'],
        'td' => $validated['td'],
        'bb' => $validated['bb'],
        'tb' => $validated['tb'],
        'suhu' => $validated['suhu'],
        'saturasiOx' => $validated['saturasiOx'],
        'lila' => $validated['lila'],
        'pemeriksaan_ibu_hamil' => $validated['pemeriksaan_ibu_hamil'],
        'pemeriksaan_ibu_nifas_kb' => $validated['pemeriksaan_ibu_nifas_kb'],
        'diagnosa' => $validated['diagnosa'],
        'tindakan' => $validated['tindakan'],
        'tgl_kembali' => $validated['tgl_kembali'],
    ]);

    // Menyusun data pivot obat
     $pivot = [];
    foreach ($request->obat_id as $index => $obatId) {
        $pivot[$obatId] = ['dosis_carkai' => $request->dosis_carkai[$index]];
    }
    $pemeriksaan->obat()->sync($pivot);

    return redirect()->route('pemeriksaan.index')->with('success', 'Data berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
  public function destroy(string $id)
{
    $pemeriksaan = Pemeriksaan::findOrFail($id);

    if ($pemeriksaan->pembayaran()->exists()) {
        return redirect()->route('pemeriksaan.index')
            ->with('error', 'Data tidak bisa dihapus karena sudah memiliki pembayaran.');
    }

    
    // Hapus relasi pivot terlebih dahulu
    $pemeriksaan->obat()->detach();

    // Baru hapus pemeriksaan
    $pemeriksaan->delete();
    return redirect()->route('pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil dihapus');
}

}
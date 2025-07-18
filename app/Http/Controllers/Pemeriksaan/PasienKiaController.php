<?php

namespace App\Http\Controllers\Pemeriksaan;


use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienKiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(Request $request)
{
    $query = Pemeriksaan::with([
        'pendaftaran.pasien',
        'pendaftaran.obat',
        'pendaftaran.pelayanan',
        'bidan'
    ])->whereHas('pendaftaran.pelayanan', function ($query) {
        $query->where('nama_pelayanan', 'KIA'); // Filter pelayanan
    });

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
       return view('pages.pemeriksaan.kia.index',compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
      public function create()
{
    return view('pages.pemeriksaan.kia.create', [
        'pasiens' => Pasien::all(),
        'pemeriksaans' => Pemeriksaan::all(),
        // Ambil pendaftaran dengan nama_pelayanan = 'Umum' dan belum punya relasi ke tabel pemeriksaan
        'pendaftarans' => Pendaftaran::doesntHave('pemeriksaan')
            ->whereHas('pelayanan', function ($query) {
                $query->where('nama_pelayanan', 'KIA');
            })
            ->with(['pasien', 'bidan', 'pelayanan'])
            ->get(),
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
        'pendaftaran_id' => 'required',
        'obat_id' => 'required|array',
        'obat_id.*' => 'required|exists:obat,id',
        'dosis_carkai' => 'required|array',
        'dosis_carkai.*' => 'required|string',
       'keluhan' => 'required|string',
    'riw_penyakit' => 'required|string',
    'td' => 'required', // Format "120/80"
    'bb' => 'required|numeric|min:1',
    'tb' => 'required|numeric|min:1',
    'suhu' => 'required|numeric|min:1|max:50',
    'saturasiOx' => 'required|numeric|min:1|max:100',
    'nadi' => 'required|numeric|min:1',
    'lila' => 'required|numeric|min:1',

    'hpht' => 'required|date',
    'hpl' => 'required|date',
    'riwayat_kehamilan_kesehatan' => 'nullable|string',
    'gpa' => 'nullable|string', 
    'umur_hamil' => 'required|date',
    'lingkar_perut' => 'required|numeric|min:1',
    'tifu' => 'required|numeric|min:1',
    'djj' => 'required|numeric|min:1',
    'ltkjanin' => 'nullable|string',
    'ktrkuterus' => 'nullable|in:Ada,Tidak Ada',
    'refla' => 'nullable|string',

    'lab' => 'nullable|string',
    'resti' => 'nullable|string',
    'diagnosa' => 'required|string',
    'intervensi' => 'nullable|string',
    'tindakan' => 'required|string',
    'tgl_kembali' => 'required|date',
    'tindak_lnjt' => 'required|in:Puskesmas,Klinik,Rumah Sakit',
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

    return redirect()->route('kia.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
}

    /**
     * Display the specified resource.
     */
   public function show( $id)
{
    $pemeriksaan = Pemeriksaan::with(['pasien', 'bidan', 'pelayanan', 'obat','pendaftaran'])
                    ->findOrFail($id);
    return view('pages.pemeriksaan.kia.show', compact('pemeriksaan'));
}


public function resume($id)
{
    $pemeriksaan = Pemeriksaan::with(['pasien', 'bidan', 'pelayanan', 'obat','pendaftaran'])
                    ->findOrFail($id);

    $pdf = Pdf::loadView('pages.export.resume.kia', compact('pemeriksaan'))
              ->setPaper('A5', 'portrait'); // ukuran mirip struk

    return $pdf->stream('resume_pemeriksaan_kia.pdf');
}

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(string $id)
{
    $pemeriksaan = Pemeriksaan::with('obat')->findOrFail($id); // muat relasi 'obat'
    $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
    $obats = Obat::all(); // semua data obat untuk dropdown

    return view('pages.pemeriksaan.kia.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validated = $request->validate([
       'pendaftaran_id' => 'required',
        'obat_id' => 'required|array',
        'obat_id.*' => 'required|exists:obat,id',
        'dosis_carkai' => 'required|array',
        'dosis_carkai.*' => 'required|string',
       'keluhan' => 'required|string',
    'riw_penyakit' => 'required|string',
    'td' => 'required', // Format "120/80"
    'bb' => 'required|numeric|min:1',
    'tb' => 'required|numeric|min:1',
    'suhu' => 'required|numeric|min:1|max:50',
    'saturasiOx' => 'required|numeric|min:1|max:100',
    'nadi' => 'required|numeric|min:1',
    'lila' => 'required|numeric|min:1',

    'hpht' => 'required|date',
    'hpl' => 'required|date',
    'riwayat_kehamilan_kesehatan' => 'nullable|string',
    'gpa' => 'nullable|string', 
    'umur_hamil' => 'required',
    'lingkar_perut' => 'required|numeric|min:1',
    'tifu' => 'required|numeric|min:1',
    'djj' => 'required|numeric|min:1',
    'ltkjanin' => 'nullable|string',
    'ktrkuterus' => 'nullable|in:Ada,Tidak Ada',
    'refla' => 'nullable|string',

    'lab' => 'nullable|string',
    'resti' => 'nullable|string',
    'diagnosa' => 'required|string',
    'intervensi' => 'nullable|string',
    'tindakan' => 'required|string',
    'tgl_kembali' => 'required|date',
    'tindak_lnjt' => 'required|in:Puskesmas,Klinik,Rumah Sakit',
    ]);

    $pemeriksaan = Pemeriksaan::findOrFail($id);

    // Update data pemeriksaan utama
    $pemeriksaan->update($validated);

    // Menyusun data pivot obat
     $pivot = [];
    foreach ($request->obat_id as $index => $obatId) {
        $pivot[$obatId] = ['dosis_carkai' => $request->dosis_carkai[$index]];
    }
    $pemeriksaan->obat()->sync($pivot);

    return redirect()->route('kia.index')->with('success', 'Data berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
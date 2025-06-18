<?php

namespace App\Http\Controllers;

use App\Models\Bidan;
use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Pendaftaran::with(['pasien', 'bidan', 'pelayanan']);

    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('pasien', function ($pasienQuery) use ($search) {
                $pasienQuery->where('nama_pasien', 'like', '%' . $search . '%')
                            ->orWhere('no_rm', 'like', '%' . $search . '%');
            })->orWhere('jenis_kunjungan', 'like', '%' . $search . '%');
        });
    }
$perPage = $request->get('per_page', 5);
    $pendaftarans = $query->paginate($perPage); 
    return view('pages.pendaftaran.index', compact('pendaftarans'));
}



    /**
     * Show the form for creating a new resource.
     */
   public function create(Request $request)
{
    return view('pages.pendaftaran.create', [
        'pasiens' => Pasien::all(),
        'bidans' => Bidan::all(),
        'pelayanans' => Pelayanan::all(),
        'selectedPasien' => $request->pasien_id,
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate([
        'pasien_id' => 'required',
        'bidan_id' => 'required',
        'tgl_daftar' => 'required|date',
        'jam_daftar' => 'required',
        'pelayanan_id' => 'required',
        'jenis_kunjungan' => 'required|in:LAMA,BARU',
    ]);

    // Generate noreg otomatis
    $last = Pendaftaran::orderBy('id', 'desc')->first();
    $nextId = $last ? $last->id + 1 : 1;
    $noreg = 'REG' . str_pad($nextId, 7, '0', STR_PAD_LEFT);

    $data = $request->all();
    $data['noreg'] = $noreg;

    Pendaftaran::create($data);

    return redirect()->route('pendaftaran.index')->with('success', 'Data berhasil ditambahkan');
}

public function export()
{
    $pendaftaran = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();

    $pdf = Pdf::loadView('pages.export.pendaftaran', compact('pendaftaran'));
    return $pdf->stream('data_pendaftaran.pdf');

}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      $pendaftaran = Pendaftaran::findOrFail($id);
    $pasiens = Pasien::all();
    $bidans = Bidan::all();
    $pelayanans = Pelayanan::all();
   

    return view('pages.pendaftaran.edit', compact('pendaftaran', 'pasiens', 'bidans','pelayanans'));
}
    

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
        'noreg' => 'required',
        'pasien_id' => 'required',
        'bidan_id' => 'required',
        'tgl_daftar' => 'required|date',
        'jam_daftar' => 'required',
        'pelayanan_id' => 'required',
        'jenis_kunjungan' => 'required|in:LAMA,BARU',
    ]);
       $pendaftaran = Pendaftaran::findOrFail($id);
    $pendaftaran->update($validated + ['updated_at' => now()]);

    return redirect()->route('pendaftaran.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
 public function destroy(string $id)
    {
       $pendaftaran = Pendaftaran::findOrFail($id);
    $pendaftaran->delete();

    return redirect()->route('pendaftaran.index')->with('success', 'Data pendaftaran berhasil dihapus');
    }

}
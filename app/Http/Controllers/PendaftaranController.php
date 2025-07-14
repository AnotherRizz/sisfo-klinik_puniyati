<?php

namespace App\Http\Controllers;

use App\Models\Bidan;
use App\Models\Pasien;
use App\Models\Pelayanan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
 

    $pemeriksaanRelations = [
        'pemeriksaanUmum.pembayaran',
        'pemeriksaanIbuNifas.pembayaran',
        'pemeriksaanKb.pembayaran',
        'pemeriksaanKiaAnak.pembayaran',
        'pemeriksaanKiaIbuHamil.pembayaran',
    ];

    // Build query awal dengan eager loading
    $query = Pendaftaran::with(array_merge(['pasien', 'bidan', 'pelayanan'], $pemeriksaanRelations));

    // Pencarian
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('pasien', function ($pasienQuery) use ($search) {
                $pasienQuery->where('nama_pasien', 'like', '%' . $search . '%')
                            ->orWhere('no_rm', 'like', '%' . $search . '%')
                            ->orWhere('alamat', 'like', '%' . $search . '%')
                             ->orWhereRaw("DATE_FORMAT(tgl_lahir, '%d-%m-%Y') LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("LOWER(CONCAT(
                                DAY(tgl_lahir), ' ',
                                CASE MONTH(tgl_lahir)
                                    WHEN 1 THEN 'januari'
                                    WHEN 2 THEN 'februari'
                                    WHEN 3 THEN 'maret'
                                    WHEN 4 THEN 'april'
                                    WHEN 5 THEN 'mei'
                                    WHEN 6 THEN 'juni'
                                    WHEN 7 THEN 'juli'
                                    WHEN 8 THEN 'agustus'
                                    WHEN 9 THEN 'september'
                                    WHEN 10 THEN 'oktober'
                                    WHEN 11 THEN 'november'
                                    WHEN 12 THEN 'desember'
                                END,
                                ' ',
                                YEAR(tgl_lahir)
                            )) LIKE ?", ["%{$search}%"]);
                            })->orWhere('jenis_kunjungan', 'like', '%' . $search . '%');
                        });
    }
    // Filter Pelayanan
if ($request->filled('pelayanan_filter')) {
    $query->where('pelayanan_id', $request->pelayanan_filter);
}


    
    $filterTanggal = $request->get('filter_tanggal', 'hari_ini');
    if ($filterTanggal === 'hari_ini') {
        $query->whereDate('tgl_daftar', now()->toDateString());
    }

    // Pagination
    $perPage = $request->get('per_page', 10);
    $pendaftarans = $query->paginate($perPage);

  
 $pelayanans = \App\Models\Pelayanan::all();
    return view('pages.pendaftaran.index', compact('pendaftarans', 'filterTanggal', 'search','pelayanans'));
}





public function cekKunjungan($pasien_id)
{
    $pernahDaftar = \App\Models\Pendaftaran::where('pasien_id', $pasien_id)->exists();

    return response()->json([
        'jenis_kunjungan' => $pernahDaftar ? 'LAMA' : 'BARU',
    ]);
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

    // Simpan data ke database
    $pendaftaran = Pendaftaran::create($data);

 if (Auth::user()->hasRole('admin')) {
    return redirect()->route('pendaftaran.index')
        ->with('success', 'Data Pendaftaran berhasil ditambahkan');
}else {
    // Redirect berdasarkan pelayanan_id
    switch ($pendaftaran->pelayanan_id) {
        case 1: // Pelayanan Umum
           return redirect()->route('umum.index')
    ->with('success', 'Data Pendaftaran berhasil ditambahkan ke pelayanan Umum');

        case 2: // Pelayanan KIA Ibu Hamil
            return redirect()->route('kia-ibu-hamil.index')
                ->with('success', 'Data Pendaftaran berhasil ditambahkan ke pelayanan Kesehatan Ibu Hamil');
        case 3: // Pelayanan Kesehatan Anak
            return redirect()->route('kia-anak.index')
                ->with('success', 'Data Pendaftaran berhasil ditambahkan ke pelayanan Kesehatan Anak');
        case 4: // Pelayanan Ibu Nifas
            return redirect()->route('nifas.index')
                ->with('success', 'Data Pendaftaran berhasil ditambahkan ke pelayanan Ibu Nifas');
        case 5: // Pelayanan KB
            return redirect()->route('kb.index')
                ->with('success', 'Data Pendaftaran berhasil ditambahkan ke pelayanan KB');
        default:
            return redirect()->route('pendaftaran.index')
                ->with('error', 'Jenis pelayanan tidak ditemukan');
    }
    }
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
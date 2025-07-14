<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienController extends Controller
{
  
public function index(Request $request)
{
    $query = Pasien::query(); // Gunakan query builder

    // Cek apakah parameter 'search' ada dalam request
  $search = $request->get('search');

if ($search = $request->get('search')) {
    $search = strtolower($search);

    $query->where(function ($q) use ($search) {
        $q->where('nama_pasien', 'like', '%' . $search . '%')
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
    });
}

    

  $perPage = $request->get('per_page', 10);
    // Ambil data dengan paginasi
    $pasiens = $query->paginate($perPage); 

    // Return view dengan data pasien
    return view('pages.pasien.index', compact('pasiens'));
}

public function cetakKIB($id)
{
    $pasien = Pasien::findOrFail($id);

    $pdf = Pdf::loadView('pages.export.kib', compact('pasien'))->setPaper('A5', 'portrait');
    return $pdf->stream('kartu_identitas_berobat_'.$pasien->no_rm.'.pdf');
}
public function export()
{
    $pasien = Pasien::all();

    $pdf = Pdf::loadView('pages.export.pasien', compact('pasien'))
     ->setPaper('a4', 'landscape');
    return $pdf->stream('data_pasien_.pdf') ; 
   
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    $request->validate([

        'nik_pasien' => 'required|size:16',
        'nama_pasien' => 'required|string|max:50',
        'tempt_lahir' => 'required|string|max:20',
        'tgl_lahir' => 'required|date',
        'jenis_kelamin' => 'required',
        'alamat' => 'required|string',
        'agama' => 'required',
        'status' => 'required',
        'pendidikan' => 'required',
        'pekerjaan' => 'required',
        'penanggungjawab' => 'required|string|max:50',
        'golda' => 'required',
        'no_tlp' => 'required|string|max:13',
    ]);

    // Generate no_rm secara otomatis
    $lastNoRm = DB::table('pasien')->max('no_rm'); // Ambil nilai tertinggi no_rm
    $newNoRm = str_pad(($lastNoRm ? $lastNoRm + 1 : 1), 6, '0', STR_PAD_LEFT); // Format menjadi 8 digit

    // Buat data baru dengan no_rm yang di-generate
   $pasien = Pasien::create([
        'no_rm' => $newNoRm,
        'nik_pasien' => $request->nik_pasien,
        'nama_pasien' => $request->nama_pasien,
        'tempt_lahir' => $request->tempt_lahir,
        'tgl_lahir' => $request->tgl_lahir,
        'umur' => $request->umur,
        'status' => $request->status,
        'jenis_kelamin' => $request->jenis_kelamin,
        'alamat' => $request->alamat,
        'agama' => $request->agama,
        'pendidikan' => $request->pendidikan,
        'pekerjaan' => $request->pekerjaan,
        'penanggungjawab' => $request->penanggungjawab,
        'golda' => $request->golda,
        'no_tlp' => $request->no_tlp,
        'nama_kk' => $request->nama_kk,
    ]);

     if ($request->source_form == 'pasien') {
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    } elseif ($request->source_form == 'pendaftaran') {
      return redirect()->route('pendaftaran.create', ['pasien_id' => $pasien->id])
        ->with('success', 'Data pasien berhasil ditambahkan.');
    }
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
      $pasien = Pasien::findOrFail($id);
    return view('pages.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
            'no_rm' => 'required|size:6',
        'nik_pasien' => 'required|numeric',
        'nama_pasien' => 'required|string|max:255',
        'tempt_lahir' => 'required|string|max:100',
        'tgl_lahir' => 'required|date',
        'jenis_kelamin' => 'required',

        'status' => 'required',
        'alamat' => 'required|string',
        'agama' => 'required|string',
        'pendidikan' => 'required|string',
        'pekerjaan' => 'required|string',
        'penanggungjawab' => 'required|string',
        'golda' => 'required|string|in:A,B,AB,O,-',
        'no_tlp' => 'required|numeric',
    ]);

    $pasien = Pasien::findOrFail($id);

    $pasien->update([
        'no_rm' => $request->no_rm,
        'nik_pasien' => $request->nik_pasien,
        'nama_pasien' => $request->nama_pasien,
        'tempt_lahir' => $request->tempt_lahir,
        'tgl_lahir' => $request->tgl_lahir,
        'umur' => $request->umur,
        'status' => $request->status,
          'jenis_kelamin' => $request->jenis_kelamin,
        'alamat' => $request->alamat,
        'agama' => $request->agama,
        'pendidikan' => $request->pendidikan,
        'pekerjaan' => $request->pekerjaan,
        'penanggungjawab' => $request->penanggungjawab,
        'golda' => $request->golda,
        'no_tlp' => $request->no_tlp,
        'nama_kk' => $request->nama_kk,
    ]);

    return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $pasien = Pasien::findOrFail($id);
    $pasien->delete();

    return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
}
}
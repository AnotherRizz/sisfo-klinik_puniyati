<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidan;
use Barryvdh\DomPDF\Facade\Pdf;

class BidanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  
public function index(Request $request)
{
    $query = Bidan::query(); 

    if ($search = $request->get('search')) {
        $query->where('nama_bidan', 'like', '%' . $search . '%')
              ->orWhere('kd_bidan', 'like', '%' . $search . '%');
    }
  $perPage = $request->get('per_page', 5);
    $bidans = $query->paginate($perPage);    

    return view('pages.bidan.index', compact('bidans'));
}

public function export()
{
    $bidan = Bidan::all();

    $pdf = Pdf::loadView('pages.export.bidan', compact('bidan'));
    return $pdf->stream('data_bidan_.pdf');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('pages.bidan.create');
}


    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'nama_bidan' => 'required|max:50',
        'alamat' => 'required',
        'no_telp' => 'required',
        'jadwal' => 'required',
    ]);

    $last = Bidan::orderBy('kd_bidan', 'desc')->first();
    if ($last) {
        $number = (int) substr($last->kd_bidan, 1) + 1;
    } else {
        $number = 1;
    }
    $kd_bidan = 'B' . str_pad($number, 3, '0', STR_PAD_LEFT);

    Bidan::create([
        'kd_bidan' => $kd_bidan,
        'nama_bidan' => $request->nama_bidan,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'jadwal' => $request->jadwal,
    ]);

    return redirect()->route('bidan.index')->with('success', 'Data bidan berhasil ditambahkan');
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
    public function edit($id)
{
    $bidan = Bidan::findOrFail($id);
    return view('pages.bidan.edit', compact('bidan'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_bidan' => 'required|max:50',
        'alamat' => 'required',
        'no_telp' => 'required',
        'jadwal' => 'required',
    ]);

    $bidan = Bidan::findOrFail($id);
    $bidan->update($request->only(['nama_bidan', 'alamat', 'no_telp', 'jadwal']));

    return redirect()->route('bidan.index')->with('success', 'Data bidan berhasil diupdate');
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $bidan = Bidan::findOrFail($id);
    $bidan->delete();

    return redirect()->route('bidan.index')->with('success', 'Data bidan berhasil dihapus');
}
}
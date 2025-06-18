<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PelayananController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
   
 public function index(Request $request)
{
    $query = Pelayanan::query();

    // Filter pencarian
    if ($search = $request->get('search')) {
        $query->where('nama_pelayanan', 'like', '%' . $search . '%')
              ->orWhere('kodpel', 'like', '%' . $search . '%');
    }

    $perPage = $request->get('per_page', 5);

    // Paginasi
    $pelayanans = $query->paginate($perPage);

    return view('pages.pelayanan.index', compact('pelayanans'));
}
public function export()
{
    $pelayanan = Pelayanan::all();

    $pdf = Pdf::loadView('pages.export.pelayanan', compact('pelayanan'));
    return $pdf->stream('data_pelayanan_.pdf');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          return view('pages.pelayanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_pelayanan' => 'required',
        
    ]);

    $last = Pelayanan::orderBy('kodpel', 'desc')->first();
    if ($last) {
        $number = (int) substr($last->kodpel, 1) + 1;
    } else {
        $number = 1;
    }
    $kodpel = 'P' . str_pad($number, 3, '0', STR_PAD_LEFT);

    Pelayanan::create([
        'kodpel' => $kodpel,
        'nama_pelayanan' => $request->nama_pelayanan,
       
    ]);

    return redirect()->route('pelayanan.index')->with('success', 'Data pelayanan berhasil ditambahkan');
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
        $pelayanan = Pelayanan::findOrFail($id);
    return view('pages.pelayanan.edit', compact('pelayanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
        'nama_pelayanan' => 'required',
        
    ]);

    $pelayanan = Pelayanan::findOrFail($id);
    $pelayanan->update([
        'nama_pelayanan' => $request->nama_pelayanan,
        
    ]);

    return redirect()->route('pelayanan.index')->with('success', 'Data pelayanan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
    $pelayanan->delete();

    return redirect()->route('pelayanan.index')->with('success', 'Data pelayanan berhasil dihapus');
    }
}
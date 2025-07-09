<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Obat;
use Barryvdh\DomPDF\Facade\Pdf;

class ObatController extends Controller
{

public function index(Request $request)
{
    $query = Obat::query();

    if ($search = $request->get('search')) {
        $query->where('nama_obat', 'like', '%' . $search . '%')
              ->orWhere('kd_obat', 'like', '%' . $search . '%');
    }

    $perPage = $request->get('per_page', 5);
    $obats = $query->paginate($perPage);

    // Cek stok rendah dan kirim ke session
    $stokRendah = Obat::where('stok_obat', '<', 10)->get(['nama_obat', 'stok_obat']);
    session(['stok_rendah' => $stokRendah]);

    return view('pages.obat.index', compact('obats'));
}



public function export()
{
    $obat = Obat::all();

    $pdf = Pdf::loadView('pages.export.obat', compact('obat'));
    return $pdf->stream('data_obat_.pdf');
}

    public function create()
    {
        return view('pages.obat.create');
    }
 public function tambahStok(Request $request, $id)
{
    $request->validate([
        'jumlah' => 'required|integer|min:1',
    ]);

    $obat = Obat::findOrFail($id);
    $obat->stok_obat += $request->jumlah;
    $obat->save();

    return redirect()->route('obat.index')->with('success', 'Stok berhasil ditambahkan.');
}


    public function store(Request $request)
{
    // Ambil kode terakhir berdasarkan urutan kd_obat (bukan id)
    $lastObat = Obat::orderBy('kd_obat', 'desc')->first();

    if ($lastObat) {
        // Ambil angka dari kd_obat terakhir, misal dari OB012 ambil 12
        $lastNumber = (int) substr($lastObat->kd_obat, 2);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    // Format ke OB001, OB002, ...
    $newCode = 'OB' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    $request->merge([
        'harga_jual' => str_replace('.', '', $request->harga_jual),
    ]);

    $request->validate([
        'nama_obat' => 'required',
        'jenis_obat' => 'required',
        'stok_obat' => 'required|integer',
        'harga_jual' => 'required|numeric',
    ]);

    Obat::create([
        'kd_obat' => $newCode,
        'nama_obat' => $request->nama_obat,
        'jenis_obat' => $request->jenis_obat,
        'stok_obat' => $request->stok_obat,
        'harga_jual' => $request->harga_jual,
    ]);

    return redirect()->route('obat.index')->with('success', 'Data Obat berhasil ditambahkan');
}


    public function edit($id)
{
    $obat = Obat::findOrFail($id);
    return view('pages.obat.edit', compact('obat'));
}

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'stok_obat' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);

        $obat->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil diperbarui');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil dihapus');
    }
}
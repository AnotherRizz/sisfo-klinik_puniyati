<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Pembayaran::with([
        'pemeriksaan.pendaftaran.pasien', // Pastikan relasi ini ada di model
        'pemeriksaan.pendaftaran.bidan',
    ]);

     if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('kd_bayar', 'like', '%' . $search . '%')
              ->orWhereHas('pemeriksaan.pendaftaran.pasien', function ($pasienQuery) use ($search) {
                  $pasienQuery->where('nama_pasien', 'like', '%' . $search . '%');
                             
              });
        });
    }

    $perPage = $request->get('per_page', 5); // Default per_page to 5
    $pembayarans = $query->paginate($perPage);

    return view('pages.pembayaran.index', compact('pembayarans'));
}

public function export()
{
    $pembayaran = Pembayaran::with(['pasien', 'bidan',  'pemeriksaan.pendaftaran'])->get();

    $pdf = Pdf::loadView('pages.export.pembayaran', compact('pembayaran'));
    return $pdf->stream('data_pembayaran.pdf');

}
public function bukti($id)
{
    $pembayaran = Pembayaran::with(['pasien', 'bidan', 'pemeriksaan.pendaftaran', 'pemeriksaan.obat'])
                    ->findOrFail($id);

    $pdf = Pdf::loadView('pages.export.bukti-bayar', compact('pembayaran'))
              ->setPaper('A5', 'portrait'); // ukuran mirip struk

    return $pdf->stream('bukti_pembayaran.pdf');
}




    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $pemeriksaans = Pemeriksaan::doesntHave('pembayaran')->with(['pendaftaran.pasien', 'pendaftaran.obat', 'pelayanan'])->get();

    return view('pages.pembayaran.create', [
        'pemeriksaans' => $pemeriksaans,
        'bidans' => Bidan::all(),
        'obats' => Obat::all(),
        'pasiens' => Pasien::all(),
        'pendaftarans' => Pendaftaran::all(),
    ]);
}


  public function store(Request $request)
{
    $data = $request->validate([
        'pemeriksaan_id' => 'required|exists:pemeriksaan,id',
        'tgl_bayar' => 'required|date',
        'administrasi' => 'nullable|string',
        'biaya_administrasi' => 'nullable|numeric',
        'tindakan' => 'nullable|string',
        'biaya_tindakan' => 'nullable|numeric',
        'jenis_bayar' => 'required|in:Tunai,Transfer',
    ]);

    // Ambil data pemeriksaan
    $pemeriksaan = Pemeriksaan::findOrFail($data['pemeriksaan_id']);

    // Ambil obat terkait
    if ($pemeriksaan->obat_id) {
        $obat = Obat::find($pemeriksaan->obat_id);

        if ($obat && $obat->stok_obat > 0) {
            $obat->stok_obat -= 1;
            $obat->save();
        } else {
            return redirect()->back()->with('error', 'Stok obat tidak mencukupi.');
        }
    }

    // Generate kode pembayaran
    $last = Pembayaran::orderBy('kd_bayar', 'desc')->first();

if ($last && preg_match('/TRX(\d+)/', $last->kd_bayar, $matches)) {
    $lastNumber = (int) $matches[1];
    $nextNumber = $lastNumber + 1;
} else {
    $nextNumber = 1;
}

$data['kd_bayar'] = 'TRX' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

    // Simpan ke database
    Pembayaran::create($data);

    return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil disimpan dan stok obat diperbarui.');
}


    /**
     * Display the specified resource.
     */
     public function show(Pembayaran $pembayaran)
{
    return view('pages.pembayaran.detail', compact('pembayaran'));
}

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(string $id)
    {
        $pembayarans = Pembayaran::findOrFail($id);
        $pasiens = Pasien::all();
        $bidans = Bidan::all();
         $pemeriksaans = Pemeriksaan::all();
        $obats = Obat::all();

        return view('pages.pembayaran.edit', compact('pembayarans','pemeriksaans',    'pasiens', 'bidans', 'obats'));
    }

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request, string $id)
    {
         $validated = $request->validate([
         'pemeriksaan_id' => 'required|exists:pemeriksaan,id',
        'tgl_bayar' => 'required|date',
        'administrasi' => 'nullable|string',
        'biaya_administrasi' => 'nullable|numeric',
        'tindakan' => 'nullable|string',
        'biaya_tindakan' => 'nullable|numeric',
        'jenis_bayar' => 'required|in:Tunai,Transfer',
        // ...tambahkan validasi sesuai kebutuhan
    ]);

    $pembayaran = Pembayaran::findOrFail($id);
    $pembayaran->update($validated + ['updated_at' => now()]);

    return redirect()->route('pembayaran.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id)
    {
       $pembayaran = Pembayaran::findOrFail($id);
    $pembayaran->delete();

    return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil dihapus');
    }



}
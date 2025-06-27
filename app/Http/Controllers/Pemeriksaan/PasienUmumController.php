<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Pelayanan;
use App\Models\PemeriksaanUmum;
use App\Models\ObatPemeriksaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
 
class PasienUmumController extends Controller
{
    public function index(Request $request)
    {
        $query = PemeriksaanUmum::with([
            'pendaftaran.pasien',
            'pendaftaran.pelayanan',
        ])->whereHas('pendaftaran.pelayanan', function ($query) {
            $query->where('nama_pelayanan', 'Umum');
        });

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('pendaftaran.pasien', function ($q) use ($search) {
                    $q->where('nama_pasien', 'like', '%' . $search . '%');
                });
            });
        }

        $perPage = $request->get('per_page', 5);
        $pemeriksaans = $query->paginate($perPage);

        return view('pages.pemeriksaan.umum.index', compact('pemeriksaans'));
    }

    public function create()
    {
        return view('pages.pemeriksaan.umum.create', [
            'pasiens' => Pasien::all(),
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanUmum')
                ->whereHas('pelayanan', function ($query) {
                    $query->where('nama_pelayanan', 'Umum');
                })
                ->with(['pasien', 'bidan', 'pelayanan'])
                ->get(),
            'bidans' => Bidan::all(),
            'obats' => Obat::all()
        ]);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'pendaftaran_id' => 'required|exists:pendaftaran,id',
        // 'nomor_periksa' => 'required|string',
        'keluhan' => 'required|string',
        'riw_penyakit' => 'required|string',
        'riw_alergi' => 'nullable|string',
        'td' => 'required',
        'bb' => 'required|numeric',
        'tb' => 'required|numeric',
        'suhu' => 'required|numeric',
        'saturasiOx' => 'required|numeric',
        'diagnosa' => 'required|string',
        'tindakan' => 'required|string',
        'tindak_lnjt' => 'required',
        'tgl_kembali' => 'required|date',
        'obat_id' => 'required|array',
        'obat_id.*' => 'exists:obat,id',
        'dosis_carkai' => 'required|array',
        'dosis_carkai.*' => 'required|string',
    ]);

    // 🟢 Generate nomor_periksa
    $last = PemeriksaanUmum::latest('id')->first();
    $nextId = $last ? $last->id + 1 : 1;
    $nomor_periksa = 'UM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

    $data = $validated;
    $data['nomor_periksa'] = $nomor_periksa;

    $pemeriksaan = PemeriksaanUmum::create($data);

    // Simpan obat yang dipakai
    foreach ($request->obat_id as $index => $obatId) {
        $pemeriksaan->obatPemeriksaan()->create([
            'obat_id' => $obatId,
            'dosis_carkai' => $request->dosis_carkai[$index],
        ]);
    }

    return redirect()->route('umum.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
}


    public function show($id)
    {
        $pemeriksaan = PemeriksaanUmum::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);
        return view('pages.pemeriksaan.umum.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanUmum::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.export.resume.umum', compact('pemeriksaan'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('resume_pemeriksaan_umum.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanUmum::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
        $obats = Obat::all();

        return view('pages.pemeriksaan.umum.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'riw_alergi' => 'nullable|string',
            'td' => 'required',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'saturasiOx' => 'required|numeric',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'dosis_carkai' => 'required|array',
            'dosis_carkai.*' => 'required|string',
        ]);

        $pemeriksaan = PemeriksaanUmum::findOrFail($id);
        $pemeriksaan->update($validated);

        // Hapus & buat ulang data obat pemeriksaan
        $pemeriksaan->obatPemeriksaan()->delete();

        foreach ($request->obat_id as $index => $obatId) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obatId,
                'dosis_carkai' => $request->dosis_carkai[$index],
            ]);
        }

        return redirect()->route('umum.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanUmum::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('umum.index')->with('success', 'Data berhasil dihapus.');
    }
}
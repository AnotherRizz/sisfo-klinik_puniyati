<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKiaAnak;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KiaAnakController extends Controller
{
    public function index(Request $request)
    {
        $query = PemeriksaanKiaAnak::with(['pendaftaran.pasien', 'pendaftaran.pelayanan'])
            ->whereHas('pendaftaran.pelayanan', fn ($q) => $q->where('nama_pelayanan', 'KIA Anak'));

        if ($request->search) {
            $query->whereHas('pendaftaran.pasien', fn ($q) =>
                $q->where('nama_pasien', 'like', '%' . $request->search . '%'));
        }

        $pemeriksaans = $query->paginate($request->per_page ?? 5);
        return view('pages.pemeriksaan.kia-anak.index', compact('pemeriksaans'));
    }

    public function create()
    {
        return view('pages.pemeriksaan.kia-anak.create', [
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKiaAnak')
                ->whereHas('pelayanan', fn ($q) => $q->where('nama_pelayanan', 'KIA Anak'))
                ->with(['pasien', 'bidan', 'pelayanan'])->get(),
            'obats' => Obat::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'nullable|string',
            'riw_imunisasi' => 'nullable|string',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'pb' => 'required|numeric',
            'lk' => 'required|numeric',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'dosis_carkai' => 'required|array',
        ]);

        $last = PemeriksaanKiaAnak::latest()->first();
        $request['nomor_periksa'] = 'KA' . str_pad(($last->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
        $pemeriksaan = PemeriksaanKiaAnak::create($request->except('obat_id', 'dosis_carkai'));

        foreach ($request->obat_id as $i => $obatId) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obatId,
                'dosis_carkai' => $request->dosis_carkai[$i],
            ]);
        }

        return redirect()->route('kia-anak.index')->with('success', 'Data KIA Anak berhasil disimpan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        return view('pages.pemeriksaan.kia-anak.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        $pdf = Pdf::loadView('pages.export.resume.kia_anak', compact('pemeriksaan'))->setPaper('A5', 'portrait');
        return $pdf->stream('resume_kia_anak.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::with('obatPemeriksaan')->findOrFail($id);
        return view('pages.pemeriksaan.kia-anak.edit', [
            'pemeriksaan' => $pemeriksaan,
            'pendaftarans' => Pendaftaran::with('pasien')->get(),
            'obats' => Obat::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
          'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'nullable|string',
            'riw_imunisasi' => 'nullable|string',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'pb' => 'required|numeric',
            'lk' => 'required|numeric',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'dosis_carkai' => 'required|array',
        ]);

        $pemeriksaan = PemeriksaanKiaAnak::findOrFail($id);
        $pemeriksaan->update($request->except('obat_id', 'dosis_carkai'));

        $pemeriksaan->obatPemeriksaan()->delete();
        foreach ($request->obat_id as $i => $obatId) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obatId,
                'dosis_carkai' => $request->dosis_carkai[$i],
            ]);
        }

        return redirect()->route('kia-anak.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKiaAnak::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kia-anak.index')->with('success', 'Data berhasil dihapus.');
    }
}
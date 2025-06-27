<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKb;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienKbController extends Controller
{
    public function index(Request $request)
    {
        $query = PemeriksaanKb::with([
            'pendaftaran.pasien',
            'pendaftaran.pelayanan',
        ])->whereHas('pendaftaran.pelayanan', function ($query) {
            $query->where('nama_pelayanan', 'KB');
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

        return view('pages.pemeriksaan.kb.index', compact('pemeriksaans'));
    }

    public function create()
    {
        return view('pages.pemeriksaan.kb.create', [
            'pasiens' => Pasien::all(),
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKb')
                ->whereHas('pelayanan', function ($query) {
                    $query->where('nama_pelayanan', 'KB');
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
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'required',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'saturasiOx' => 'required|numeric',
            'alergi' => 'required',
            'hpht' => 'nullable|date',
            'jmlhanak' => 'nullable|numeric',
            'tglpasang' => 'nullable|date',
            'metode_kb' => 'nullable|in:pil,Suntik,Implan,IUD,Kondom,MOW/MOP',
            'edukasi' => 'nullable|string',
            'intervensi' => 'nullable|string',
            'efek_samping' => 'nullable|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'dosis_carkai' => 'required|array',
            'dosis_carkai.*' => 'required|string',
        ]);

        // generate nomor_periksa otomatis
        $last = PemeriksaanKb::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $nomor_periksa = 'KB' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $data = $validated;
        $data['nomor_periksa'] = $nomor_periksa;

        $pemeriksaan = PemeriksaanKb::create($data);

        foreach ($request->obat_id as $index => $obatId) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obatId,
                'dosis_carkai' => $request->dosis_carkai[$index],
            ]);
        }

        return redirect()->route('kb.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanKb::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);
        return view('pages.pemeriksaan.kb.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKb::with(['pendaftaran.pasien', 'obatPemeriksaan.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.export.resume.kb', compact('pemeriksaan'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('resume_pemeriksaan_kb.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKb::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with(['pasien', 'bidan', 'pelayanan'])->get();
        $obats = Obat::all();

        return view('pages.pemeriksaan.kb.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'required',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'saturasiOx' => 'required|numeric',
            'alergi' => 'required',
            'hpht' => 'nullable|date',
            'jmlhanak' => 'nullable|numeric',
            'tglpasang' => 'nullable|date',
            'metode_kb' => 'nullable|in:pil,Suntik,Implan,IUD,Kondom,MOW/MOP',
            'edukasi' => 'nullable|string',
            'intervensi' => 'nullable|string',
            'efek_samping' => 'nullable|string',
            'tindak_lnjt' => 'required',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'dosis_carkai' => 'required|array',
            'dosis_carkai.*' => 'required|string',
        ]);

        $pemeriksaan = PemeriksaanKb::findOrFail($id);
        $pemeriksaan->update($validated);

        $pemeriksaan->obatPemeriksaan()->delete();
        foreach ($request->obat_id as $index => $obatId) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obatId,
                'dosis_carkai' => $request->dosis_carkai[$index],
            ]);
        }

        return redirect()->route('kb.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKb::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kb.index')->with('success', 'Data berhasil dihapus.');
    }
}
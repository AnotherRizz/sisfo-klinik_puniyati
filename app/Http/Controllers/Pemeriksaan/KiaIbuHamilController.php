<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use App\Models\Bidan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PemeriksaanKiaIbuHamil;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KiaIbuHamilController extends Controller
{
    public function index(Request $request)
    {
        $query = PemeriksaanKiaIbuHamil::with(['pendaftaran.pasien', 'pendaftaran.pelayanan'])
            ->whereHas('pendaftaran.pelayanan', function ($q) {
                $q->where('nama_pelayanan', 'KIA Ibu Hamil');
            });

        if ($search = $request->search) {
            $query->whereHas('pendaftaran.pasien', function ($q) use ($search) {
                $q->where('nama_pasien', 'like', '%' . $search . '%');
            });
        }

        $pemeriksaans = $query->paginate($request->per_page ?? 5);
        return view('pages.pemeriksaan.kia-ibu-hamil.index', compact('pemeriksaans'));
    }

    public function create()
    {
        return view('pages.pemeriksaan.kia-ibu-hamil.create', [
            'pendaftarans' => Pendaftaran::doesntHave('pemeriksaanKiaIbuHamil')
                ->whereHas('pelayanan', fn ($q) => $q->where('nama_pelayanan', 'KIA Ibu Hamil'))
                ->with(['pasien', 'bidan', 'pelayanan'])->get(),
            'obats' => Obat::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'required|string',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'saturasiOx' => 'required|numeric',
            'nadi' => 'required|numeric',
            'lila' => 'required|numeric',
            'hpht' => 'required|date',
            'hpl' => 'required|date',
            'gpa' => 'nullable|string',
            'riwayatkehamilankesehatan' => 'nullable|string',
            'umr_hamil' => 'required',
            'ling_perut' => 'required|numeric',
            'tifu' => 'required|numeric',
            'djj' => 'required|numeric',
            'ltkjanin' => 'required',
            'ktrkuterus' => 'required',
            'refla' => 'required',
            'lab' => 'required',
            'resti' => 'required',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'dosis_carkai' => 'required|array',
        ]);

        $last = PemeriksaanKiaIbuHamil::latest()->first();
        $request['nomor_periksa'] = 'KH' . str_pad(($last->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
        $pemeriksaan = PemeriksaanKiaIbuHamil::create($request->except('obat_id', 'dosis_carkai'));

        foreach ($request->obat_id as $i => $obat_id) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obat_id,
                'dosis_carkai' => $request->dosis_carkai[$i],
            ]);
        }

        return redirect()->route('kia-ibu-hamil.index')->with('success', 'Berhasil menyimpan data KIA Ibu Hamil.');
    }

    public function show($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        return view('pages.pemeriksaan.kia-ibu-hamil.show', compact('pemeriksaan'));
    }

    public function resume($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('pendaftaran.pasien', 'obatPemeriksaan.obat')->findOrFail($id);
        $pdf = Pdf::loadView('pages.export.resume.kia-ibu-hamil', compact('pemeriksaan'))->setPaper('A5', 'portrait');
        return $pdf->stream('resume_kia_ibu.pdf');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::with('obatPemeriksaan')->findOrFail($id);
        $pendaftarans = Pendaftaran::with('pasien', 'pelayanan')->get();
        $obats = Obat::all();
        return view('pages.pemeriksaan.kia-ibu-hamil.edit', compact('pemeriksaan', 'pendaftarans', 'obats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
             'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'keluhan' => 'required|string',
            'riw_penyakit' => 'required|string',
            'td' => 'required|string',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'suhu' => 'required|numeric',
            'saturasiOx' => 'required|numeric',
            'nadi' => 'required|numeric',
            'lila' => 'required|numeric',
            'hpht' => 'required|date',
            'hpl' => 'required|date',
            'gpa' => 'nullable|string',
            'riwayatkehamilankesehatan' => 'nullable|string',
            'umr_hamil' => 'required',
            'ling_perut' => 'required|numeric',
            'tifu' => 'required|numeric',
            'djj' => 'required|numeric',
            'ltkjanin' => 'required',
            'ktrkuterus' => 'required',
            'refla' => 'required',
            'lab' => 'required',
            'resti' => 'required',
            'diagnosa' => 'required|string',
            'intervensi' => 'required|string',
            'tindak_lnjt' => 'required|string',
            'tgl_kembali' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'dosis_carkai' => 'required|array',
        ]);

        $pemeriksaan = PemeriksaanKiaIbuHamil::findOrFail($id);
        $pemeriksaan->update($request->except('obat_id', 'dosis_carkai'));

        $pemeriksaan->obatPemeriksaan()->delete();
        foreach ($request->obat_id as $i => $obat_id) {
            $pemeriksaan->obatPemeriksaan()->create([
                'obat_id' => $obat_id,
                'dosis_carkai' => $request->dosis_carkai[$i],
            ]);
        }

        return redirect()->route('kia-ibu-hamil.index')->with('success', 'Berhasil memperbarui data KIA Ibu Hamil.');
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanKiaIbuHamil::findOrFail($id);
        $pemeriksaan->obatPemeriksaan()->delete();
        $pemeriksaan->delete();

        return redirect()->route('kia-ibu.index')->with('success', 'Data berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\PemeriksaanUmum;
use App\Models\PemeriksaanKb;
use App\Models\PemeriksaanKiaIbuHamil;
use App\Models\PemeriksaanKiaAnak;
use App\Models\PemeriksaanIbuNifas;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $pasien = Pasien::count();
   $pendaftaranHariIni = Pendaftaran::whereDate('tgl_daftar', Carbon::today())->count();


    $pemeriksaan = 
        PemeriksaanUmum::count() +
        PemeriksaanKb::count() +
        PemeriksaanKiaIbuHamil::count() +
        PemeriksaanKiaAnak::count() +
        PemeriksaanIbuNifas::count();

    return view('pages.dashboard', compact('pasien', 'pemeriksaan', 'pendaftaranHariIni'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
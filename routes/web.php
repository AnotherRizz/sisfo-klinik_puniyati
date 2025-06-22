<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterData\BidanController;
use App\Http\Controllers\MasterData\ObatController;
use App\Http\Controllers\MasterData\PasienController;
use App\Http\Controllers\MasterData\PelayananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Pemeriksaan\IbuNifasController;
use App\Http\Controllers\Pemeriksaan\PasienKbController;
use App\Http\Controllers\Pemeriksaan\PasienKiaController;
use App\Http\Controllers\Pemeriksaan\PasienUmumController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\SettingController;
use App\Models\Pelayanan;
use App\Models\Pemeriksaan;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Barryvdh\DomPDF\Facade\Pdf;


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('pendaftaran', PendaftaranController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('setting', SettingController::class);
    Route::post('/user/profile', [SettingController::class, 'update'])->name('user.update');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
// export data
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pendaftaran', [LaporanController::class, 'exportPendaftaran'])->name('laporan.pendaftaran');
    Route::get('/laporan/pemeriksaan', [LaporanController::class, 'exportPemeriksaan'])->name('laporan.pemeriksaan');
    Route::get('/laporan/pembayaran', [LaporanController::class, 'exportPembayaran'])->name('laporan.pembayaran');
    Route::get('/pasien/{id}/cetak-kib', [PasienController::class, 'cetakKIB'])->name('pasien.cetak.kib');
    Route::get('/pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('/bidan/export', [BidanController::class, 'export'])->name('bidan.export');
    Route::get('/obat/export', [ObatController::class, 'export'])->name('obat.export');
    Route::get('/pelayanan/export', [PelayananController::class, 'export'])->name('pelayanan.export');
    Route::get('/pdf/export', [PendaftaranController::class, 'export'])->name('pdf.export');
    Route::get('/pmb/export', [PembayaranController::class, 'export'])->name('pmb.export');
    Route::get('/pmb/{id}/bukti-bayar', [PembayaranController::class, 'bukti'])->name('pmb.bukti-bayar');
    Route::get('export', [LaporanController::class, 'export'])->name('all.export');

// preview
Route::get('/laporan/pendaftaran/preview', [LaporanController::class, 'previewPendaftaran'])->name('laporan.pendaftaran.preview');
Route::get('/laporan/pemeriksaan/preview', [LaporanController::class, 'previewPemeriksaan'])->name('laporan.pemeriksaan.preview');
Route::get('/laporan/pembayaran/preview', [LaporanController::class, 'previewPembayaran'])->name('laporan.pembayaran.preview');



    Route::prefix('master')->group(function () {
        Route::resource('pasien', PasienController::class);
        Route::resource('bidan', BidanController::class);
        Route::resource('pelayanan', PelayananController::class);
        Route::resource('obat', ObatController::class);
        Route::post('/obat/{id}/tambah-stok', [ObatController::class, 'tambahStok'])->name('obat.tambahStok');

    });


     Route::prefix('pemeriksaan')->group(function () {
        Route::resource('umum', PasienUmumController::class);
        Route::get('/umum/{id}/resume', [PasienUmumController::class, 'resume'])->name('umum.resume');
    
        Route::resource('kia', PasienKiaController::class);
        Route::get('/kia/{id}/resume', [PasienKiaController::class, 'resume'])->name('kia.resume');
        Route::resource('nifas', IbuNifasController::class);
        Route::get('/nifas/{id}/resume', [IbuNifasController::class, 'resume'])->name('nifas.resume');
        Route::resource('kb', PasienKbController::class);
        Route::get('/kb/{id}/resume', [PasienKbController::class, 'resume'])->name('kb.resume');
});


});
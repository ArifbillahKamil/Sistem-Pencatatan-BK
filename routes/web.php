<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Root → redirect to login
Route::get('/', function () {
    return redirect('/login');
});

// ── Authenticated routes ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard (both roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PDF Export Routes
    Route::get('/siswa/{id}/export-pdf', [\App\Http\Controllers\PdfController::class, 'exportSiswa'])->name('pdf.siswa');
    Route::get('/kelas/{id}/export-pdf', [\App\Http\Controllers\PdfController::class, 'exportKelas'])->name('pdf.kelas')->middleware(['role:guru_bk']);

    // ── GURU BK routes ──────────────────────────────────────────────
    Route::middleware(['role:guru_bk'])->group(function () {

        // Kelas (Step 8)
        Route::resource('kelas', \App\Http\Controllers\KelasController::class)->parameters(['kelas' => 'kela']);

        // Siswa (Step 9)
        Route::resource('siswa', \App\Http\Controllers\SiswaController::class);

        // Jenis Pelanggaran (Step 10)
        Route::resource('jenis-pelanggaran', \App\Http\Controllers\JenisPelanggaranController::class);

        // Users (Step 8 – managed with Kelas)
        Route::resource('users', \App\Http\Controllers\UserController::class);

        // Transaksi Pelanggaran (Step 11)
        Route::get('transaksi/search-siswa', [\App\Http\Controllers\TransaksiPelanggaranController::class, 'searchSiswa'])->name('transaksi.searchSiswa');
        Route::resource('transaksi', \App\Http\Controllers\TransaksiPelanggaranController::class);

        // Log Peringatan (Step 12)
        Route::get('/log-peringatan', [\App\Http\Controllers\LogPeringatanController::class, 'index'])->name('log-peringatan.index');
        Route::get('/log-peringatan/{siswa}', [\App\Http\Controllers\LogPeringatanController::class, 'show'])->name('log-peringatan.show');
        Route::post('/log-peringatan/{log}/toggle', [\App\Http\Controllers\LogPeringatanController::class, 'toggleStatus'])->name('log-peringatan.toggle');

    });

    // ── WALI KELAS routes (Step 13) ──────────────────────────────────
    Route::middleware(['role:wali_kelas'])->group(function () {
        Route::get('/wali/siswa',        [\App\Http\Controllers\WaliKelasController::class, 'siswa'])->name('wali.siswa');
        Route::get('/wali/pelanggaran',  [\App\Http\Controllers\WaliKelasController::class, 'pelanggaran'])->name('wali.pelanggaran');
        Route::get('/wali/sp',           [\App\Http\Controllers\WaliKelasController::class, 'sp'])->name('wali.sp');
    });

    // ── GURU WALI routes ─────────────────────────────────────────────
    Route::middleware(['role:guru_wali'])->prefix('guru-wali')->name('guru_wali.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\GuruWaliController::class, 'dashboard'])->name('dashboard');
        Route::get('/assignment/search', [\App\Http\Controllers\GuruWaliController::class, 'searchSiswa'])->name('assignment.search');
        Route::get('/assignment', [\App\Http\Controllers\GuruWaliController::class, 'assignment'])->name('assignment');
        Route::post('/assignment/save', [\App\Http\Controllers\GuruWaliController::class, 'saveAssignment'])->name('assignment.save');
        Route::get('/siswa', [\App\Http\Controllers\GuruWaliController::class, 'listSiswa'])->name('siswa.index');
        Route::get('/siswa/{id}', [\App\Http\Controllers\GuruWaliController::class, 'detailSiswa'])->name('siswa.detail');
    });

});

require __DIR__.'/auth.php';

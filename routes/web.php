<?php

use App\Http\Controllers\AlternatifController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/send-otp', [ResetPasswordController::class, 'sendOtp'])->name('password.email');
Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.verify.otp');
Route::get('/reset-password-form', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password-form', [ResetPasswordController::class, 'updatePassword'])->name('password.reset.update');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('KelolaBobotKriteria.index');
    Route::get('/kriteria/create', [KriteriaController::class, 'tambah'])->name('kriteria.tambah');
    Route::post('/kriteria', [KriteriaController::class, 'save'])->name('kriteria.save');
    Route::get('/kriteria/editKriteria/{id}', [KriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::post('/kriteria/{id}/edit', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/{id}', [KriteriaController::class, 'delete'])->name('kriteria.hapus');


    Route::get('/Alternatif', [AlternatifController::class, 'index'])->name('Alternatif.index');
    Route::delete('/Alternatif/{id}', [AlternatifController::class, 'delete'])->name('Alternatif.delete');
    Route::get('/Alternatif/create', [AlternatifController::class, 'create']);
    Route::post('/Alternatif', [AlternatifController::class, 'store']);
    Route::get('/Alternatif/{id}/edit', [AlternatifController::class, 'edit'])->name('Alternatif.edit');
    Route::put('/Alternatif/{id}', [AlternatifController::class, 'update'])->name('Alternatif.update');
    Route::post('/Alternatif/import', [AlternatifController::class, 'import'])->name('Alternatif.import');
    Route::get('/Alternatif/export', [AlternatifController::class, 'export'])->name('Alternatif.export');



    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::get('/nilai/create', [NilaiController::class, 'create']);
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai');
    Route::get('/nilai/Alternatif/{nik}/edit', [NilaiController::class, 'edit']);
    Route::put('/nilai/Alternatif/{nik}', [NilaiController::class, 'update']);
    Route::delete('/nilai/Alternatif/{nik}', [NilaiController::class, 'destroy']);
    Route::post('/nilai/import', [NilaiController::class, 'import']);
    Route::get('/nilai/export', [NilaiController::class, 'export']);

    Route::get('/lihatPerhitungan', [PerhitunganController::class, 'index']);
    Route::get('/lihatPerhitungan/eliminasi', [PerhitunganController::class, 'eliminasi']);
    Route::get('/lihatPerhitungan/cetakPDF', [PerhitunganController::class, 'cetakPDF']);
    Route::get('/lihatPerhitungan/cetakEliminasiPDF', [PerhitunganController::class, 'eliminasiPDF']);

    Route::get('/import', [KriteriaController::class, 'index'])->name('import.indexExcel');
    Route::post('/import', [KriteriaController::class, 'import'])->name('import.excel');
    Route::get('/export-excel', [KriteriaController::class, 'export'])->name('export.excel');
});

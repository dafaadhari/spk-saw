<?php

use App\Http\Controllers\TendikController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\NilaiController;

Route::get('/', function () {
    return view('welcome');
});

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


    Route::get('/tendik', [TendikController::class, 'index'])->name('tendik.index');
    Route::delete('/tendik/{id}', [TendikController::class, 'delete'])->name('tendik.delete');
    Route::get('/tendik/create', [TendikController::class, 'create']);
    Route::post('/tendik', [TendikController::class, 'store']);
    Route::get('/tendik/{id}/edit', [TendikController::class, 'edit'])->name('tendik.edit');
    Route::put('/tendik/{id}', [TendikController::class, 'update'])->name('tendik.update');
    Route::post('/tendik/import', [TendikController::class, 'import'])->name('tendik.import');
    Route::get('/tendik/export', [TendikController::class, 'export'])->name('tendik.export');



    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::get('/nilai/create', [NilaiController::class, 'create']);
    Route::post('/nilai', [NilaiController::class, 'store']);
    Route::get('/nilai/{id}/edit', [NilaiController::class, 'edit']);
    Route::put('/nilai/{id}', [NilaiController::class, 'update']);
    Route::delete('/nilai/{id}', [NilaiController::class, 'destroy']);

    Route::get('/lihatPerhitungan', [PerhitunganController::class, 'index']);
    Route::get('/lihatPerhitungan/cetakPDF', [PerhitunganController::class, 'cetakPDF']);

    Route::get('/import', [KriteriaController::class, 'index'])->name('import.indexExcel');
    Route::post('/import', [KriteriaController::class, 'import'])->name('import.excel');
    Route::get('/export-excel', [KriteriaController::class, 'export'])->name('export.excel');
});

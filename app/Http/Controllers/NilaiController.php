<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Tendik;
use App\Models\Kriteria;

class NilaiController extends Controller
{
    // Index Nilai
    public function index()
    {
        $nilais = Nilai::with(['tendik', 'kriteria'])->get();
        return view('KelolaPenilaian.index', ['data' => $nilais]);
    }

    // Form Tambah Nilai
    public function create()
    {
        $tendiks = Tendik::all();
        $kriterias = Kriteria::all();
        return view('KelolaPenilaian.create', [
            'tendiks' => $tendiks,
            'kriterias' => $kriterias
        ]);
    }

    // Simpan Nilai Baru
    public function store(Request $request)
    {
        $request->validate([
            'tendik_id' => 'required|exists:tendiks,id',
            'kriteria_id' => 'required|exists:kriterias,id',
            'value' => 'required|numeric|min:0|max:100'
        ], [
            'tendik_id.required' => 'Nama tendik wajib dipilih.',
            'kriteria_id.required' => 'Nama kriteria wajib dipilih.',
            'value.required' => 'Nilai wajib diisi.',
            'value.numeric' => 'Nilai harus berupa angka.',
            'value.min' => 'Nilai minimal adalah 0.',
            'value.max' => 'Nilai maksimal adalah 100.',
        ]);


        Nilai::create([
            'tendik_id' => $request->tendik_id,
            'kriteria_id' => $request->kriteria_id,
            'value' => $request->value,
        ]);

        return redirect('/nilai')->with('success', 'Data penilaian berhasil ditambahkan.');
    }


    // Form Edit Nilai
    public function edit($id)
    {
        $nilais = Nilai::findOrFail($id);
        $tendiks = Tendik::all();
        $kriterias = Kriteria::all();
        return view('KelolaPenilaian.edit', [
            'data' => $nilais,
            'tendiks' => $tendiks,
            'kriterias' => $kriterias
        ]);
    }

    // Update Nilai
    public function update(Request $request, $id)
    {
        $request->validate([
            'tendik_id' => 'required|exists:tendiks,id',
            'kriteria_id' => 'required|exists:kriterias,id',
            'value' => 'required|numeric|min:0|max:100'
        ], [
            'tendik_id.required' => 'Nama tendik wajib dipilih.',
            'kriteria_id.required' => 'Nama kriteria wajib dipilih.',
            'value.required' => 'Nilai wajib diisi.',
            'value.numeric' => 'Nilai harus berupa angka.',
            'value.min' => 'Nilai minimal adalah 0.',
            'value.max' => 'Nilai maksimal adalah 100.',
        ]);

        $nilai = Nilai::findOrFail($id);
        $nilai->update([
            'tendik_id' => $request->tendik_id,
            'kriteria_id' => $request->kriteria_id,
            'value' => $request->value,
        ]);

        return redirect('/nilai')->with('success', 'Data penilaian berhasil diperbarui.');
    }

    // Hapus Nilai
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();
        return redirect('/nilai')->with('success', 'Data penilain berhasil dihapus.');
    }
}

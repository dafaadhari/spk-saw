<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Tendik;
use App\Models\Kriteria;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'tendik_nik' => 'required|array',
            'tendik_nik.*' => 'required|exists:tendiks,nik',
            'kode_kriteria' => 'required|array',
            'kode_kriteria.*' => 'required|exists:kriterias,kode_kriteria',
            'value' => 'required|array',
            'value.*' => 'required|numeric|min:0|max:100',
        ], [
            'tendik_nik.*.required' => 'Nama tendik wajib dipilih.',
            'tendik_nik.*.exists' => 'Tendik tidak valid.',
            'kode_kriteria.*.required' => 'Nama kriteria wajib dipilih.',
            'kode_kriteria.*.exists' => 'Kriteria tidak valid.',
            'value.*.required' => 'Nilai wajib diisi.',
            'value.*.numeric' => 'Nilai harus berupa angka.',
            'value.*.min' => 'Nilai minimal adalah 0.',
            'value.*.max' => 'Nilai maksimal adalah 100.',
        ]);

        $data = [];
        $errors = new MessageBag();

        for ($i = 0; $i < count($request->tendik_nik); $i++) {
            $nik = $request->tendik_nik[$i];
            $kode = $request->kode_kriteria[$i];

            $exists = Nilai::where('tendik_nik', $nik)
                ->where('kode_kriteria', $kode)
                ->exists();

            if ($exists) {
                $tendikNama = Tendik::where('nik', $nik)->value('nama');
                $kriteriaNama = Kriteria::where('kode_kriteria', $kode)->value('nama');

                $errors->add("duplikat_$i", "Baris " . ($i + 1) . ": Penilaian untuk $tendikNama dengan kriteria $kriteriaNama sudah ada.");
                continue;
            }

            $data[] = [
                'tendik_nik' => $nik,
                'kode_kriteria' => $kode,
                'value' => $request->value[$i],
            ];
        }

        if ($errors->isNotEmpty()) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        Nilai::insert($data);

        return redirect('/nilai')->with('success', 'Data penilaian berhasil ditambahkan.');
    }

    // Form Edit Nilai
    public function edit($id)
    {
        $nilais = Nilai::findOrFail($id);
        $tendiks = Tendik::all();

        // Ambil semua kriteria yang belum digunakan oleh tendik tersebut, kecuali yang sedang dipakai
        $kriterias = Kriteria::whereNotIn('kode_kriteria', function ($query) use ($nilais) {
            $query->select('kode_kriteria')
                ->from('nilais')
                ->where('tendik_nik', $nilais->tendik_nik)
                ->where('id', '!=', $nilais->id); // kecualikan data yang sedang diedit
        })->get();

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
            'tendik_nik' => 'required|exists:tendiks,nik',
            'kode_kriteria' => 'required|exists:kriterias,kode_kriteria',
            'value' => 'required|numeric|min:0|max:100'
        ], [
            'tendik_nik.required' => 'Nama tendik wajib dipilih.',
            'kode_kriteria.required' => 'Nama kriteria wajib dipilih.',
            'value.required' => 'Nilai wajib diisi.',
            'value.numeric' => 'Nilai harus berupa angka.',
            'value.min' => 'Nilai minimal adalah 0.',
            'value.max' => 'Nilai maksimal adalah 100.',
        ]);

        $nilai = Nilai::findOrFail($id);
        $nilai->update([
            'tendik_nik' => $request->tendik_nik,
            'kode_kriteria' => $request->kode_kriteria,
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

    // Import Excell
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        $errors = [];
        $inserted = 0;

        foreach ($sheet as $index => $row) {
            if ($index === 0) continue; // Skip header

            $tendik_nik = trim($row[0]);
            $kode_kriteria = trim($row[1]);
            $value = is_numeric($row[2]) ? (float) $row[2] : 0;

            if (DB::table('nilais')
                ->where('tendik_nik', $tendik_nik)
                ->where('kode_kriteria', $kode_kriteria)
                ->exists()
            ) {

                $errors[] = "Baris " . ($index + 1) . ": Penilaian untuk NIK \"$tendik_nik\" dan Kriteria \"$kode_kriteria\" sudah ada.";
                continue;
            }


            Nilai::create([
                'tendik_nik' => $tendik_nik,
                'kode_kriteria' => $kode_kriteria,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inserted++;
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors(new MessageBag($errors))
                ->with('success', "$inserted data berhasil diimport, " . count($errors) . " duplikat diabaikan.");
        }

        return redirect('/nilai')->with('success', 'Semua data tendik berhasil diimport!');
    }

    // Export Excell
    public function export()
    {
        $nilais = Nilai::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tendik NIK');
        $sheet->setCellValue('B1', 'Kode Kriteria');
        $sheet->setCellValue('C1', 'Value');

        // Data
        $row = 2;
        foreach ($nilais as $nilai) {
            $sheet->setCellValue("A{$row}", $nilai->tendik_nik);
            $sheet->setCellValue("B{$row}", $nilai->kode_kriteria);
            $sheet->setCellValue("C{$row}", $nilai->value);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_nilai.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

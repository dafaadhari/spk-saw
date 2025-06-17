<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Kriteria;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class KriteriaController extends Controller
{
    protected $kriteria;

    public function __construct(Kriteria $kriteria)
    {
        $this->kriteria = $kriteria;
    }

    public function index()
    {
        $kriterias = $this->kriteria->all();
        return view('KelolaBobotKriteria.index', compact('kriterias'));
    }

    public function tambah()
    {
        return view('KelolaBobotKriteria.formtambah');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sumber' => 'required',
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0.',
            'bobot.max' => 'Bobot tidak boleh lebih dari 1.',
            'sumber.required' => 'Sumber wajib dipilih.',
            'sumber.in' => 'Sumber harus di isi',
        ]);

        $this->kriteria->create([
            'nama' => $validated['nama_kriteria'],
            'weight' => $validated['bobot'],
            'sumber' => $validated['sumber'],
        ]);

        return redirect('/kriteria')->with('success', 'Kriteria berhasil disimpan.');
    }

    public function edit($id)
    {
        $kriteria = $this->kriteria->findOrFail($id);
        return view('KelolaBobotKriteria.formedit', compact('kriteria'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sumber' => 'required',
        ],[
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0.',
            'bobot.max' => 'Bobot tidak boleh lebih dari 1.',
            'sumber.required' => 'Sumber wajib dipilih.',
            'sumber.in' => 'Sumber harus di isi',
        ]);

        $kriteria = $this->kriteria->findOrFail($id);
        $kriteria->update([
            'nama' => $validated['nama_kriteria'],
            'weight' => $validated['bobot'],
            'sumber' => $validated['sumber'],
        ]);

        return redirect('/kriteria')->with('success', 'Kriteria berhasil diperbarui.');
    }
    public function delete($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('KelolaBobotKriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }

    public function indexExcel()
    {
        return redirect('/kriteria');
    }

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

            $nama = trim($row[0]);
            $weight = $row[1];
            $sumber = $row[2];

            // Cek jika nama sudah ada di database
            $exists = DB::table('kriterias')->where('nama', $nama)->exists();

            if ($exists) {
                $errors[] = "Baris " . ($index + 1) . ": Nama kriteria \"$nama\" sudah ada.";
                continue;
            }

            // Insert data
            Kriteria::create([
                'nama' => $nama,
                'weight' => $weight,
                'sumber' => $sumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inserted++;
        }

        // Jika ada error, tampilkan semua
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->with('success', "$inserted data berhasil diimport, " . count($errors) . " duplikat diabaikan.");
        }

        return redirect()->route('import.indexExcel')->with('success', 'Data berhasil diimport!');
    }

    public function export()
    {
        $kriterias = Kriteria::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Bobot');
        $sheet->setCellValue('C1', 'Sumber');

        // Data
        $row = 2;
        foreach ($kriterias as $kriteria) {
            $sheet->setCellValue("A{$row}", $kriteria->nama);
            $sheet->setCellValue("B{$row}", $kriteria->weight);
            $sheet->setCellValue("C{$row}", $kriteria->sumber);
            $row++;
        }

        // Buat file dan download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_kriteria.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

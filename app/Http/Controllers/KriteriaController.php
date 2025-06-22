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
            'kode_kriteria' => 'required|string|max:35|unique:kriterias,kode_kriteria',
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sumber' => 'required|string|max:255',
        ], [
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.unique' => 'Kode kriteria sudah digunakan.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0.',
            'bobot.max' => 'Bobot tidak boleh lebih dari 1.',
            'sumber.required' => 'Sumber wajib diisi.',
        ]);

        $this->kriteria->create([
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama' => $validated['nama_kriteria'],
            'weight' => $validated['bobot'],
            'sumber' => $validated['sumber'],
        ]);

        return redirect('/kriteria')->with('success', 'Kriteria berhasil disimpan.');
    }

    public function edit($kode_kriteria)
    {
        $kriteria = $this->kriteria->where('kode_kriteria', $kode_kriteria)->firstOrFail();
        return view('KelolaBobotKriteria.formedit', compact('kriteria'));
    }

    public function update(Request $request, $kode_kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sumber' => 'required|string|max:255',
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0.',
            'bobot.max' => 'Bobot tidak boleh lebih dari 1.',
            'sumber.required' => 'Sumber wajib diisi.',
        ]);

        $kriteria = $this->kriteria->where('kode_kriteria', $kode_kriteria)->firstOrFail();
        $kriteria->update([
            'nama' => $validated['nama_kriteria'],
            'weight' => $validated['bobot'],
            'sumber' => $validated['sumber'],
        ]);

        return redirect('/kriteria')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function delete($kode_kriteria)
    {
        $kriteria = $this->kriteria->where('kode_kriteria', $kode_kriteria)->firstOrFail();
        $kriteria->delete();

        return redirect('/kriteria')->with('success', 'Kriteria berhasil dihapus.');
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

            $kode = trim($row[0] ?? '');
            $nama = trim($row[1] ?? '');
            $weight = trim($row[2] ?? '');
            $sumber = trim($row[3] ?? '');

            if ($kode === '' || $nama === '' || !is_numeric($weight) || $sumber === '') {
                $errors[] = "Baris " . ($index + 1) . ": Data tidak lengkap atau tidak valid.";
                continue;
            }


            // Cek jika kode_kriteria sudah ada
            $exists = DB::table('kriterias')->where('kode_kriteria', $kode)->exists();
            if ($exists) {
                $errors[] = "Baris " . ($index + 1) . ": Kode kriteria \"$kode\" sudah ada.";
                continue;
            }

            Kriteria::create([
                'kode_kriteria' => $kode,
                'nama' => $nama,
                'weight' => $weight,
                'sumber' => $sumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inserted++;
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->with('success', "$inserted data berhasil diimport, " . count($errors) . " duplikat/invalid diabaikan.");
        }

        return redirect()->route('import.indexExcel')->with('success', 'Semua data berhasil diimport!');
    }

    public function export()
    {
        $kriterias = Kriteria::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'kode_kriteria');
        $sheet->setCellValue('B1', 'nama');
        $sheet->setCellValue('C1', 'bobot');
        $sheet->setCellValue('D1', 'sumber');

        // Data
        $row = 2;
        foreach ($kriterias as $kriteria) {
            $sheet->setCellValue("A{$row}", $kriteria->kode_kriteria);
            $sheet->setCellValue("B{$row}", $kriteria->nama);
            $sheet->setCellValue("C{$row}", $kriteria->weight);
            $sheet->setCellValue("D{$row}", $kriteria->sumber);
            $row++;
        }

        // Buat file dan kirim ke browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_kriteria.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

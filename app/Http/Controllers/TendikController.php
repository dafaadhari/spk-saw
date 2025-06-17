<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Tendik;

class TendikController extends Controller
{
    public function index()
    {
        $tendik = Tendik::with('user')->get();
        return view('KelolaDataTendik.index', ['data' => $tendik]);
    }

    public function delete($id)
    {
        $tendik = Tendik::findOrFail($id);
        $tendik->delete();

        return redirect()->back()->with('success', 'Data tendik berhasil dihapus.');
    }

    public function create()
    {
        return view('KelolaDataTendik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits_between:1,16|unique:tendiks,nik',
            'unit_kerja' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits_between' => 'NIK maksimal 16 digit.',
            'nik.unique' => 'NIK sudah digunakan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
            'user_id.required' => 'User ID tidak ditemukan.',
        ]);

        Tendik::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'unit_kerja' => $request->unit_kerja,
            'user_id' => $request->user_id,
        ]);

        return redirect('/tendik')->with('success', 'Data tendik berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tendik = Tendik::findOrFail($id);
        return view('KelolaDataTendik.edit', compact('tendik'));
    }

    public function update(Request $request, $id)
    {
        $tendik = Tendik::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits_between:1,16|unique:tendiks,nik,' . $tendik->id,
            'unit_kerja' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits_between' => 'NIK maksimal 16 digit.',
            'nik.unique' => 'NIK sudah digunakan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
        ]);

        $tendik->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'unit_kerja' => $request->unit_kerja,
            'user_id' => $request->user_id,
        ]);

        return redirect('/tendik')->with('success', 'Data tendik berhasil diperbarui!');
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
            $nik = trim($row[1]);
            $unit_kerja = trim($row[2]);

            if (DB::table('tendiks')->where('nik', $nik)->exists()) {
                $errors[] = "Baris " . ($index + 1) . ": NIk \"$nik\" sudah ada.";
                continue;
            }

            Tendik::create([
                'user_id' =>Auth::id(),
                'nama' => $nama,
                'nik' => $nik,
                'unit_kerja' => $unit_kerja,
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

        return redirect()->route('tendik.index')->with('success', 'Data tendik berhasil diimport!');
    }

    public function export()
    {
        $tendiks = Tendik::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Unit Kerja');

        // Data isi
        $row = 2;
        foreach ($tendiks as $tendik) {
            $sheet->setCellValue("A{$row}", $tendik->nama);
            $sheet->setCellValue("B{$row}", $tendik->nik);
            $sheet->setCellValue("C{$row}", $tendik->unit_kerja);
            $row++;
        }

        // Simpan sementara lalu kirim ke user
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_tendik.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

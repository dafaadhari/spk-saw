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
            'nik' => 'required|string|size:16|unique:tendiks,nik',
            'unit_kerja' => 'required|string|max:255',
            'jenis_pegawai' => 'required|string|max:255',
            'jam_kerja_tahunan' => 'required|integer|min:0',
            'jam_kerja_bulanan' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 karakter.',
            'nik.unique' => 'NIK sudah digunakan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
            'jenis_pegawai.required' => 'Jenis pegawai wajib diisi.',
            'jam_kerja_tahunan.required' => 'Jam kerja tahunan wajib diisi.',
            'jam_kerja_bulanan.required' => 'Jam kerja bulanan wajib diisi.',
            'user_id.required' => 'User ID tidak ditemukan.',
        ]);

        Tendik::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'unit_kerja' => $request->unit_kerja,
            'jenis_pegawai' => $request->jenis_pegawai,
            'jam_kerja_tahunan' => $request->jam_kerja_tahunan,
            'jam_kerja_bulanan' => $request->jam_kerja_bulanan,
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
            'nik' => 'required|string|size:16|unique:tendiks,nik,' . $tendik->nik . ',nik',
            'unit_kerja' => 'required|string|max:255',
            'jenis_pegawai' => 'required|string|max:255',
            'jam_kerja_tahunan' => 'required|integer|min:0',
            'jam_kerja_bulanan' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 karakter.',
            'nik.unique' => 'NIK sudah digunakan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
            'jenis_pegawai.required' => 'Jenis pegawai wajib diisi.',
            'jam_kerja_tahunan.required' => 'Jam kerja tahunan wajib diisi.',
            'jam_kerja_bulanan.required' => 'Jam kerja bulanan wajib diisi.',
            'user_id.required' => 'User ID tidak ditemukan.',
        ]);

        $tendik->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'unit_kerja' => $request->unit_kerja,
            'jenis_pegawai' => $request->jenis_pegawai,
            'jam_kerja_tahunan' => $request->jam_kerja_tahunan,
            'jam_kerja_bulanan' => $request->jam_kerja_bulanan,
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

            $nik = trim($row[0]);
            $nama = trim($row[1]);
            $unit_kerja = trim($row[2]);
            $jenis_pegawai = trim($row[3]);
            $jam_kerja_tahunan = is_numeric($row[4]) ? (int) $row[4] : 0;
            $jam_kerja_bulanan = is_numeric($row[5]) ? (float) $row[5] : 0;

            if (DB::table('tendiks')->where('nik', $nik)->exists()) {
                $errors[] = "Baris " . ($index + 1) . ": NIK \"$nik\" sudah ada.";
                continue;
            }

            Tendik::create([
                'user_id' => Auth::id(),
                'nik' => $nik,
                'nama' => $nama,
                'unit_kerja' => $unit_kerja,
                'jenis_pegawai' => $jenis_pegawai,
                'jam_kerja_tahunan' => $jam_kerja_tahunan,
                'jam_kerja_bulanan' => $jam_kerja_bulanan,
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

        return redirect()->route('tendik.index')->with('success', 'Semua data tendik berhasil diimport!');
    }

    public function export()
    {
        $tendiks = Tendik::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIK');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Unit Kerja');
        $sheet->setCellValue('D1', 'Jenis Pegawai');
        $sheet->setCellValue('E1', 'Jam Kerja Tahunan');
        $sheet->setCellValue('F1', 'Jam Kerja Bulanan');

        // Data
        $row = 2;
        foreach ($tendiks as $tendik) {
            $sheet->setCellValue("A{$row}", $tendik->nik);
            $sheet->setCellValue("B{$row}", $tendik->nama);
            $sheet->setCellValue("C{$row}", $tendik->unit_kerja);
            $sheet->setCellValue("D{$row}", $tendik->jenis_pegawai);
            $sheet->setCellValue("E{$row}", $tendik->jam_kerja_tahunan);
            $sheet->setCellValue("F{$row}", $tendik->jam_kerja_bulanan);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_tendik.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    // Index Nilai
    public function index()
    {
        $kriterias = Kriteria::all();

        // Ambil semua tendik dengan relasi nilai
        $tendiks = Tendik::with(['nilais'])->get();

        return view('KelolaPenilaian.index', [
            'kriterias' => $kriterias,
            'tendiks' => $tendiks
        ]);
    }

    // Form Tambah Nilai
    public function create()
    {
        $tendiks = Tendik::all();
        $kriterias = Kriteria::all();
        return view('KelolaPenilaian.create', compact('tendiks', 'kriterias'));
    }

    // Simpan Nilai Baru
    public function store(Request $request)
    {
        $request->validate([
            'tendik_nik' => 'required|exists:tendiks,nik',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ], [
            'tendik_nik.required' => 'Tendik wajib dipilih.',
            'tendik_nik.exists' => 'Tendik tidak valid.',
            'nilai.*.required' => 'Semua nilai wajib diisi.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai minimal 0.',
            'nilai.*.max' => 'Nilai maksimal 100.',
        ]);

        $nik = $request->tendik_nik;
        $data = [];
        $errors = new \Illuminate\Support\MessageBag();

        foreach ($request->nilai as $kode_kriteria => $value) {
            $exists = Nilai::where('tendik_nik', $nik)
                ->where('kode_kriteria', $kode_kriteria)
                ->exists();

            if ($exists) {
                $kriteriaNama = Kriteria::where('kode_kriteria', $kode_kriteria)->value('nama');
                $errors->add("duplikat_{$kode_kriteria}", "Penilaian untuk kriteria $kriteriaNama sudah ada.");
                continue;
            }

            $data[] = [
                'tendik_nik' => $nik,
                'kode_kriteria' => $kode_kriteria,
                'value' => $value,
            ];
        }

        if ($errors->isNotEmpty()) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        Nilai::insert($data);

        return redirect('/nilai')->with('success', 'Data penilaian berhasil ditambahkan.');
    }

    // Form Edit Nilai
    public function edit($nik)
    {
        $tendik = Tendik::where('nik', $nik)->firstOrFail();
        $kriterias = Kriteria::all();

        // Ambil nilai yang sudah ada untuk tendik ini (per kriteria)
        $nilaiMap = Nilai::where('tendik_nik', $nik)
            ->pluck('value', 'kode_kriteria'); // hasil: [kode_kriteria => nilai]

        return view('KelolaPenilaian.edit', [
            'tendik' => $tendik,
            'kriterias' => $kriterias,
            'nilaiMap' => $nilaiMap,
        ]);
    }

    // Update Nilai
    public function update(Request $request, $nik)
    {
        $request->validate([
            'tendik_nik' => 'required|exists:tendiks,nik',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $kode_kriteria => $value) {
            Nilai::updateOrCreate(
                [
                    'tendik_nik' => $request->tendik_nik,
                    'kode_kriteria' => $kode_kriteria
                ],
                ['value' => $value]
            );
        }

        return redirect('/nilai')->with('success', 'Nilai berhasil diperbarui.');
    }

    // Hapus Nilai
    public function destroy($nik)
    {
        $tendik = Tendik::where('nik', $nik)->firstOrFail();

        // Hapus semua nilai yang terkait dengan tendik ini
        Nilai::where('tendik_nik', $nik)->delete();

        return redirect('/nilai')->with('success', 'Semua data penilaian untuk ' . $tendik->nama . ' berhasil dihapus.');
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
        $logSuccess = [];

        if (count($sheet) < 2) {
            return back()->withErrors(['File kosong atau tidak memiliki data.']);
        }

        $header = $sheet[0]; // Header baris 1
        $kodeKriterias = array_map('trim', array_slice($header, 2)); // Kolom C dst (KRITERIA), abaikan NIK dan NAMA

        foreach ($sheet as $index => $row) {
            if ($index === 0) continue;

            $nik = trim($row[0]);
            if (!$nik) {
                $errors[] = "Baris " . ($index + 1) . ": NIK kosong.";
                continue;
            }

            $tendik = Tendik::where('nik', $nik)->first();
            if (!$tendik) {
                $errors[] = "Baris " . ($index + 1) . ": NIK \"$nik\" tidak ditemukan.";
                continue;
            }

            foreach ($kodeKriterias as $colIndex => $kode_kriteria) {
                $kode_kriteria = strtoupper(trim($kode_kriteria));
                $value = $row[$colIndex + 2] ?? null; // +2 karena kolom C dst

                // Kolom tidak cukup
                if (!isset($row[$colIndex + 2])) {
                    $errors[] = "Baris " . ($index + 1) . ": Kolom untuk \"$kode_kriteria\" kosong.";
                    continue;
                }

                // Validasi kriteria
                if (!Kriteria::whereRaw('UPPER(kode_kriteria) = ?', [$kode_kriteria])->exists()) {
                    $errors[] = "Baris " . ($index + 1) . ": Kode kriteria \"$kode_kriteria\" tidak valid.";
                    continue;
                }

                if (!is_numeric($value)) {
                    $errors[] = "Baris " . ($index + 1) . ": Nilai \"$kode_kriteria\" tidak valid.";
                    continue;
                }

                if (Nilai::where('tendik_nik', $nik)->where('kode_kriteria', $kode_kriteria)->exists()) {
                    $errors[] = "Baris " . ($index + 1) . ": Nilai NIK \"$nik\" & \"$kode_kriteria\" sudah ada.";
                    continue;
                }

                Nilai::create([
                    'tendik_nik' => $nik,
                    'kode_kriteria' => $kode_kriteria,
                    'value' => (float) $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $inserted++;
                $logSuccess[] = "[SUKSES] NIK: $nik | KRITERIA: $kode_kriteria | NILAI: $value";
            }
        }

        // LOG
        $logText = now()->toDateTimeString() . " - IMPORT NILAI\n";
        $logText .= "Total berhasil: $inserted\n";
        $logText .= "Total gagal: " . count($errors) . "\n\n";
        $logText .= implode("\n", $logSuccess);
        if (!empty($errors)) {
            $logText .= "\n\n[ERROR]\n" . implode("\n", $errors);
        }
        Log::channel('single')->info($logText);

        if (!empty($errors)) {
            return back()
                ->withErrors(new MessageBag($errors))
                ->with('success', "$inserted nilai berhasil diimpor. " . count($errors) . " gagal.");
        }

        return redirect('/nilai')->with('success', "$inserted nilai berhasil diimpor.");
    }

    public function export()
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        $tendiks = Tendik::with('nilais')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'NIK');
        $sheet->setCellValue('B1', 'NAMA');

        foreach ($kriterias as $index => $kriteria) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 3); // Mulai dari kolom C (karena A=NIK, B=NAMA)
            $sheet->setCellValue("{$colLetter}1", strtoupper($kriteria->kode_kriteria));
        }

        // Data baris per tendik
        $row = 2;
        foreach ($tendiks as $tendik) {
            $sheet->setCellValue("A{$row}", $tendik->nik);
            $sheet->setCellValue("B{$row}", $tendik->nama); // tambahkan nama

            // Buat map nilai: KODE_KRITERIA => value
            $nilaiMap = $tendik->nilais
                ->mapWithKeys(function ($item) {
                    return [strtoupper($item->kode_kriteria) => $item->value];
                });

            foreach ($kriterias as $index => $kriteria) {
                $colLetter = Coordinate::stringFromColumnIndex($index + 3); // Kolom C dst
                $value = $nilaiMap->get(strtoupper($kriteria->kode_kriteria)) ?? '';
                $sheet->setCellValue("{$colLetter}{$row}", $value);
            }

            $row++;
        }

        // Styling header
        $lastCol = Coordinate::stringFromColumnIndex(count($kriterias) + 2); // +2 karena A dan B
        $headerRange = "A1:{$lastCol}1";

        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A0A0A0'); // abu
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF'); // putih

        // Simpan dan download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'penilaian_tendik_' . now()->format('Ymd_His') . '.xlsx';
        $path = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($path);

        return response()->download($path, $fileName)->deleteFileAfterSend(true);
    }
}

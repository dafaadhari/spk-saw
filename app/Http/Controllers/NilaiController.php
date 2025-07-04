<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Alternatif;
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
        $jumlahKriteria = $kriterias->count();
        $kodeKriteriaResmi = $kriterias->pluck('kode_kriteria')->map(fn($x) => strtoupper($x))->toArray();

        // Ambil semua Alternatif dengan relasi nilai
        $alternatifs = Alternatif::with(['nilais'])->get();

        $filteredalternatifs = $alternatifs->filter(function ($Alternatif) use ($kodeKriteriaResmi) {
            $kodeNilai = $Alternatif->nilais->pluck('kode_kriteria')->map(fn($x) => strtoupper($x))->unique()->toArray();
            // Cek apakah semua kode kriteria resmi ada di nilai
            return count(array_intersect($kodeKriteriaResmi, $kodeNilai)) === count($kodeKriteriaResmi);
        });

        return view('KelolaPenilaian.index', [
            'kriterias' => $kriterias,
            'alternatifs' => $filteredalternatifs
        ]);
    }

    // Form Tambah Nilai
    public function create()
    {
        $alternatifs = Alternatif::all();
        $kriterias = Kriteria::all();
        return view('KelolaPenilaian.create', compact('alternatifs', 'kriterias'));
    }

    // Simpan Nilai Baru
    public function store(Request $request)
    {
        $request->validate([
            'alternatif_nik' => 'required|exists:alternatifs,nik',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ], [
            'alternatif_nik.required' => 'Alternatif wajib dipilih.',
            'alternatif_nik.exists' => 'Alternatif tidak valid.',
            'nilai.*.required' => 'Semua nilai wajib diisi.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai minimal 0.',
            'nilai.*.max' => 'Nilai maksimal 100.',
        ]);

        $nik = $request->alternatif_nik;
        $data = [];
        $errors = new \Illuminate\Support\MessageBag();

        foreach ($request->nilai as $kode_kriteria => $value) {
            $exists = Nilai::where('alternatif_nik', $nik)
                ->where('kode_kriteria', $kode_kriteria)
                ->exists();

            if ($exists) {
                $kriteriaNama = Kriteria::where('kode_kriteria', $kode_kriteria)->value('nama');
                $errors->add("duplikat_{$kode_kriteria}", "Penilaian untuk kriteria $kriteriaNama sudah ada.");
                continue;
            }

            $data[] = [
                'alternatif_nik' => $nik,
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
        $Alternatif = Alternatif::where('nik', $nik)->firstOrFail();
        $kriterias = Kriteria::all();

        // Ambil nilai yang sudah ada untuk Alternatif ini (per kriteria)
        $nilaiMap = Nilai::where('alternatif_nik', $nik)
            ->pluck('value', 'kode_kriteria'); // hasil: [kode_kriteria => nilai]

        return view('KelolaPenilaian.edit', [
            'Alternatif' => $Alternatif,
            'kriterias' => $kriterias,
            'nilaiMap' => $nilaiMap,
        ]);
    }

    // Update Nilai
    public function update(Request $request, $nik)
    {
        $request->validate([
            'alternatif_nik' => 'required|exists:alternatifs,nik',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $kode_kriteria => $value) {
            Nilai::updateOrCreate(
                [
                    'alternatif_nik' => $request->alternatif_nik,
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
        $Alternatif = Alternatif::where('nik', $nik)->firstOrFail();

        // Hapus semua nilai yang terkait dengan Alternatif ini
        Nilai::where('alternatif_nik', $nik)->delete();

        return redirect('/nilai')->with('success', 'Semua data penilaian untuk ' . $Alternatif->nama . ' berhasil dihapus.');
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

        $header = $sheet[0];
        $kodeKriterias = array_map('trim', array_slice($header, 1));

        foreach ($sheet as $index => $row) {
            if ($index === 0) continue;

            $nik = trim($row[0]);

            if (!$nik) {
                $errors[] = "Baris " . ($index + 1) . ": NIK kosong.";
                continue;
            }

            $Alternatif = Alternatif::where('nik', $nik)->first();
            if (!$Alternatif) {
                $errors[] = "Baris " . ($index + 1) . ": NIK \"$nik\" tidak ditemukan.";
                continue;
            }

            foreach ($kodeKriterias as $colIndex => $kode_kriteria) {
                $kode_kriteria = strtoupper(trim($kode_kriteria));
                $value = $row[$colIndex + 1] ?? null;

                if (!Kriteria::where('kode_kriteria', $kode_kriteria)->exists()) {
                    $errors[] = "Baris " . ($index + 1) . ": Kode kriteria \"$kode_kriteria\" tidak valid.";
                    continue;
                }

                if (!is_numeric($value)) {
                    $errors[] = "Baris " . ($index + 1) . ": Nilai \"$kode_kriteria\" tidak valid.";
                    continue;
                }

                if (Nilai::where('alternatif_nik', $nik)->where('kode_kriteria', $kode_kriteria)->exists()) {
                    $errors[] = "Baris " . ($index + 1) . ": Nilai NIK \"$nik\" & \"$kode_kriteria\" sudah ada.";
                    continue;
                }

                Nilai::create([
                    'alternatif_nik' => $nik,
                    'kode_kriteria' => $kode_kriteria,
                    'value' => (float) $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $inserted++;
                $logSuccess[] = "[SUKSES] NIK: $nik | KRITERIA: $kode_kriteria | NILAI: $value";
            }
        }

        // Logging
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

        return redirect('/nilai')->with('success', "$inserted data penilaian berhasil diimpor.");
    }

    public function export()
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        $alternatifs = Alternatif::with('nilais')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'NIK');

        foreach ($kriterias as $index => $kriteria) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 2); // Kolom B++
            $sheet->setCellValue("{$colLetter}1", strtoupper($kriteria->kode_kriteria)); // UPPERCASE
        }

        // Data baris per Alternatif
        $row = 2;
        foreach ($alternatifs as $Alternatif) {
            $sheet->setCellValue("A{$row}", $Alternatif->nik);

            foreach ($kriterias as $index => $kriteria) {
                $colLetter = Coordinate::stringFromColumnIndex($index + 2);
                $nilai = $Alternatif->nilais->firstWhere('kode_kriteria', $kriteria->kode_kriteria);
                $sheet->setCellValue("{$colLetter}{$row}", $nilai->value ?? '');
            }

            $row++;
        }

        // Styling header: abu-abu gelap + teks putih bold
        $lastCol = Coordinate::stringFromColumnIndex(count($kriterias) + 1);
        $headerRange = "A1:{$lastCol}1";

        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A0A0A0'); // grey
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF'); // white

        // Simpan dan download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'penilaian_Alternatif_' . now()->format('Ymd_His') . '.xlsx';
        $path = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($path);

        return response()->download($path, $fileName)->deleteFileAfterSend(true);
    }
}

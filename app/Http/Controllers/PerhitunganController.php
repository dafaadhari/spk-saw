<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Tendik;
use App\Models\Nilai;


class PerhitunganController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $tendiks = Tendik::all();

        // Ambil nilai maksimum untuk setiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        // Hitung nilai akhir SAW untuk setiap tendik
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('tendik_nik', $tendik->nik)
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first();

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_nik' => $tendik->nik,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($tendik->jam_kerja_bulanan),
            ];
        }

        // Urutkan berdasarkan nilai akhir secara menurun
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking dan hasil ke database
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'tendik_nik' => $res['tendik_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }

        // Kirim ke view lengkap
        return view('LihatPerhitungan.index', [
            'hasil' => $results
        ]);
    }

    public function eliminasi()
    {
        $kriterias = Kriteria::all();
        $tendiks = Tendik::all();

        // Ambil nilai maksimum untuk setiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        // Hitung nilai akhir SAW untuk setiap tendik
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('tendik_nik', $tendik->nik)
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first();

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_nik' => $tendik->nik,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($tendik->jam_kerja_bulanan),
            ];
        }

        // Urutkan berdasarkan nilai akhir secara menurun
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking ke DB
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'tendik_nik' => $res['tendik_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }

        // Filter hanya yang tereliminasi
        $eliminated = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] < 160);

        // Kirim hanya data tereliminasi ke view
        return view('LihatPerhitungan.eliminasi', [
            'hasil' => array_values($eliminated) // reset index
        ]);
    }



    public function cetakPDF()
    {
        $kriterias = Kriteria::all();
        $tendiks = Tendik::all();

        // Ambil nilai maksimum untuk setiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        // Hitung nilai akhir SAW untuk setiap tendik
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('tendik_nik', $tendik->nik)
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first();

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_nik' => $tendik->nik,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($tendik->jam_kerja_bulanan),
            ];
        }

        // Urutkan berdasarkan nilai akhir secara menurun
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking dan hasil ke database
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'tendik_nik' => $res['tendik_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }



        // Kirim ke view (jika ada)
        return view('LihatPerhitungan.cetakPDF', ['hasil' => $results]);
    }

    public function eliminasiPDF()
    {
        $kriterias = Kriteria::all();
        $tendiks = Tendik::all();

        // Ambil nilai maksimum untuk setiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        // Hitung nilai akhir SAW untuk setiap tendik
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('tendik_nik', $tendik->nik)
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first();

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_nik' => $tendik->nik,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($tendik->jam_kerja_bulanan),
            ];
        }

        // Urutkan berdasarkan nilai akhir secara menurun
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking ke DB
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'tendik_nik' => $res['tendik_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }

        // Filter hanya yang tereliminasi
        $eliminated = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] < 160);

        // Kirim hanya data tereliminasi ke view
        return view('LihatPerhitungan.cetakEliminasiPDF', [
            'hasil' => array_values($eliminated) // reset index
        ]);
    }
}

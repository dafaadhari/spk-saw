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
        $tendiks = Tendik::with('nilais')->get();

        // 1. Ambil nilai maksimum tiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kriteria_id', $kriteria->id)->max('value');
            $maxValues[$kriteria->id] = $max > 0 ? $max : 1;
        }

        // 2. Hitung nilai akhir per tendik
        $results = [];
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = $tendik->nilais->where('kriteria_id', $kriteria->id)->first();
                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->id];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_id' => $tendik->id,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
            ];
        }

        // 3. Urutkan berdasarkan nilai akhir dari terbesar ke terkecil
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // 4. Kosongkan hasil sebelumnya dan simpan yang baru
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1; // Kembalikan rank ke angka

            Hasil::create([
                'tendik_id' => $res['tendik_id'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }


        // Kirim ke view (jika ada)
        return view('LihatPerhitungan.index', ['hasil' => $results]);
    }

    public function cetakPDF()
    {
        $kriterias = Kriteria::all();
        $tendiks = Tendik::with('nilais')->get();

        // 1. Ambil nilai maksimum tiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kriteria_id', $kriteria->id)->max('value');
            $maxValues[$kriteria->id] = $max > 0 ? $max : 1;
        }

        // 2. Hitung nilai akhir per tendik
        $results = [];
        foreach ($tendiks as $tendik) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = $tendik->nilais->where('kriteria_id', $kriteria->id)->first();
                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->id];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'tendik_id' => $tendik->id,
                'nama' => $tendik->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
            ];
        }

        // 3. Urutkan berdasarkan nilai akhir dari terbesar ke terkecil
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // 4. Kosongkan hasil sebelumnya dan simpan yang baru
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1; // Kembalikan rank ke angka

            Hasil::create([
                'tendik_id' => $res['tendik_id'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }


        // Kirim ke view (jika ada)
        return view('LihatPerhitungan.cetakPDF', ['hasil' => $results]);
    }
}

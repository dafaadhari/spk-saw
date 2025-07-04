<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Nilai;
use Illuminate\Pagination\LengthAwarePaginator;


class PerhitunganController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('entries', 10);

        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::all();

        // Hitung max value per kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        foreach ($alternatifs as $Alternatif) {
            $nilai_akhir = 0;
            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('alternatif_nik', $Alternatif->nik) /* Untuk Mencocokan Nilai antara Data Kriteria dengan Data Alternatif dan Tendik(NIK)*/
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first(); /* Mengambil  data perbaris */

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria]; /* Normalisasi, Dibagi Nilai Maximum Perkriteria*/
                    $nilai_akhir += $kriteria->weight * $normalized; /* Hitung Hasil Akhir, Hasil Normalisasi x Bobot + Hasil Semua Nilai Kriteria*/
                }
            }

            $results[] = [
                'alternatif_nik' => $Alternatif->nik,
                'nama' => $Alternatif->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($Alternatif->jam_kerja_bulanan),
            ];
        }

        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking ke DB
        Hasil::truncate();
        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'alternatif_nik' => $res['alternatif_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }

        // Pencarian
        $filtered = array_filter($results, function ($item) use ($search) {
            if (!$search) return true;
            return str_contains(strtolower($item['alternatif_nik'] . $item['nama']), strtolower($search));
        });

        // Pisahkan lolos dan eliminasi
        $lolos = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] >= 160);
        $eliminasi = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] < 160);

        // Urutkan berdasarkan rank agar tampilan sesuai
        usort($lolos, fn($a, $b) => $a['rank'] <=> $b['rank']);
        usort($eliminasi, fn($a, $b) => $b['rank'] <=> $a['rank']);

        // Paginate manual
        // $lolosPaginator = $this->paginateArray(array_values($lolos), $perPage, $request, 'lolos_page');
        $lolosPaginator = collect(array_values($lolos));
        $eliminasiPaginator = $this->paginateArray(array_values($eliminasi), $perPage, $request, 'eliminasi_page');

        // dd($lolosPaginator, $eliminasi);
        return view('LihatPerhitungan.index', [
            'lolos' => $lolosPaginator,
            'eliminasi' => $eliminasiPaginator,
            'search' => $search,
            'entries' => $perPage
        ]);
    }

    private function paginateArray(array $items, $perPage, Request $request, $pageName)
    {
        $page = LengthAwarePaginator::resolveCurrentPage($pageName);
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        return new LengthAwarePaginator(
            $paginatedItems,
            count($items),
            $perPage,
            $page,
            ['path' => $request->url(), 'pageName' => $pageName]
        );
    }


    public function cetakPDF()
    {
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::all();

        // Ambil nilai maksimum untuk setiap kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

        // Hitung nilai akhir SAW untuk setiap Alternatif
        foreach ($alternatifs as $Alternatif) {
            $nilai_akhir = 0;

            foreach ($kriterias as $kriteria) {
                $nilai = Nilai::where('alternatif_nik', $Alternatif->nik)
                    ->where('kode_kriteria', $kriteria->kode_kriteria)
                    ->first();

                if ($nilai) {
                    $normalized = $nilai->value / $maxValues[$kriteria->kode_kriteria];
                    $nilai_akhir += $kriteria->weight * $normalized;
                }
            }

            $results[] = [
                'alternatif_nik' => $Alternatif->nik,
                'nama' => $Alternatif->nama,
                'nilai_akhir' => round($nilai_akhir, 4),
                'jam_kerja_bulanan' => floatval($Alternatif->jam_kerja_bulanan),
            ];
        }

        // Urutkan berdasarkan nilai akhir secara menurun
        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Simpan ranking dan hasil ke database
        Hasil::truncate();

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;

            Hasil::create([
                'alternatif_nik' => $res['alternatif_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }
        // Tambahkan ini sebelum return
        $lolos = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] >= 160);
        $eliminasi = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] < 160);

        return view('LihatPerhitungan.cetakPDF', [
            'lolos' => array_values($lolos),
            'eliminasi' => array_values($eliminasi),
        ]);
    }
}

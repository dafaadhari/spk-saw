<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Tendik;
use App\Models\Nilai;
use Illuminate\Pagination\LengthAwarePaginator;


class PerhitunganController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('entries', 10);

        $kriterias = Kriteria::all();
        $tendiks = Tendik::all();

        // Hitung max value per kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        $results = [];

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

        // Pencarian
        $filtered = array_filter($results, function ($item) use ($search) {
            if (!$search) return true;
            return str_contains(strtolower($item['tendik_nik'] . $item['nama']), strtolower($search));
        });

        // Pisahkan lolos dan eliminasi
        $lolos = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] >= 160);
        $eliminasi = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] < 160);

        // Paginate manual
        $lolosPaginator = $this->paginateArray(array_values($lolos), $perPage, $request, 'lolos_page');
        $eliminasiPaginator = $this->paginateArray(array_values($eliminasi), $perPage, $request, 'eliminasi_page');

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
        // Tambahkan ini sebelum return
        $lolos = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] >= 160);
        $eliminasi = array_filter($results, fn($r) => $r['jam_kerja_bulanan'] < 160);

        return view('LihatPerhitungan.cetakPDF', [
            'lolos' => array_values($lolos),
            'eliminasi' => array_values($eliminasi),
        ]);
    }
}

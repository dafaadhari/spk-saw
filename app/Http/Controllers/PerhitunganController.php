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

        // Nilai max per kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        // Analisa Kualitatif
        $analisa_kualitatif = [];
        foreach ($alternatifs as $alt) {
            $row = ['nama' => $alt->nama];
            foreach ($kriterias as $k) {
                $nilai = Nilai::where('alternatif_nik', $alt->nik)->where('kode_kriteria', $k->kode_kriteria)->first();
                $row[$k->kode_kriteria] = $nilai ? $nilai->value : '-';
            }
            $analisa_kualitatif[] = $row;
        }

        // Skoring Kuantitatif
        $skoring_kuantitatif = [];
        foreach ($alternatifs as $alt) {
            $row = ['nik' => $alt->nik];
            foreach ($kriterias as $k) {
                $nilai = Nilai::where('alternatif_nik', $alt->nik)->where('kode_kriteria', $k->kode_kriteria)->first();
                $row[$k->kode_kriteria] = $nilai ? $nilai->value : 0;
            }
            $skoring_kuantitatif[] = $row;
        }

        // Normalisasi & perangkingan
        $results = [];
        $normalisasi = [];
        $perangkingan = [];

        foreach ($alternatifs as $alt) {
            $total = 0;
            $row_norm = ['nik' => $alt->nik];
            $row_bobot = ['nik' => $alt->nik];

            foreach ($kriterias as $k) {
                $nilai = Nilai::where('alternatif_nik', $alt->nik)->where('kode_kriteria', $k->kode_kriteria)->first();
                $val = $nilai ? $nilai->value : 0;
                $norm = $val / $maxValues[$k->kode_kriteria];
                $bobot = $norm * $k->weight;

                $row_norm[$k->kode_kriteria] = round($norm, 2);
                $row_bobot[$k->kode_kriteria] = round($bobot, 4);
                $total += $bobot;
            }

            $row_bobot['total'] = round($total, 4);
            $normalisasi[] = $row_norm;
            $perangkingan[] = $row_bobot;

            $results[] = [
                'alternatif_nik' => $alt->nik,
                'nama' => $alt->nama,
                'nilai_akhir' => round($total, 4),
                'jam_kerja_bulanan' => floatval($alt->jam_kerja_bulanan),
            ];
        }

        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);
        Hasil::truncate();
        foreach ($results as $i => &$res) {
            $res['rank'] = $i + 1;
            Hasil::create([
                'alternatif_nik' => $res['alternatif_nik'],
                'final_hasil' => $res['nilai_akhir'],
                'rank' => $res['rank'],
            ]);
        }

        $filtered = array_filter($results, fn($item) => !$search || str_contains(strtolower($item['alternatif_nik'] . $item['nama']), strtolower($search)));
        $lolos = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] >= 160);
        $eliminasi = array_filter($filtered, fn($r) => $r['jam_kerja_bulanan'] < 160);
        usort($lolos, fn($a, $b) => $a['rank'] <=> $b['rank']);
        usort($eliminasi, fn($a, $b) => $a['rank'] <=> $b['rank']);

        // --- Perangkingan dan Normalisasi (sudah ada di kode lama) ---
        $perangkingan = [];
        foreach ($alternatifs as $alt) {
            $total = 0;
            $row_bobot = ['nik' => $alt->nik];
            foreach ($kriterias as $k) {
                $nilai = Nilai::where('alternatif_nik', $alt->nik)->where('kode_kriteria', $k->kode_kriteria)->first();
                $val = $nilai ? $nilai->value : 0;
                $norm = $val / $maxValues[$k->kode_kriteria];
                $bobot = $norm * $k->weight;
                $row_bobot[$k->kode_kriteria] = round($bobot, 4);
                $total += $bobot;
            }
            $row_bobot['total'] = round($total, 4);
            $perangkingan[] = $row_bobot;
        }

        // --- SORT & ASSIGN RANK ---
        // Urutkan berdasarkan total (DESC)
        usort($perangkingan, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        // Assign Rank
        foreach ($perangkingan as $i => &$row) {
            $row['rank'] = $row['total'] > 0 ? $i + 1 : '-';
        }
        unset($row); // reference safety

        // --- PASSING DATA KE VIEW ---
        return view('LihatPerhitungan.index', [
            'search' => $search,
            'entries' => $perPage,
            'kriterias' => $kriterias,
            'alternatifs' => $alternatifs,
            'analisa_kualitatif' => $analisa_kualitatif,
            'skoring_kuantitatif' => $skoring_kuantitatif,
            'normalisasi' => $normalisasi,
            'perangkingan' => $perangkingan,
            'lolos' => collect(array_values($lolos)),
            'eliminasi' => $this->paginateArray(array_values($eliminasi), $perPage, $request, 'eliminasi_page'),
        ]);
    }

    private function paginateArray(array $items, $perPage, Request $request, $pageName)
    {
        $page = LengthAwarePaginator::resolveCurrentPage($pageName);
        $offset = ($page - 1) * $perPage;
        return new LengthAwarePaginator(
            array_slice($items, $offset, $perPage),
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

        // Nilai max per kriteria
        $maxValues = [];
        foreach ($kriterias as $kriteria) {
            $max = Nilai::where('kode_kriteria', $kriteria->kode_kriteria)->max('value');
            $maxValues[$kriteria->kode_kriteria] = $max > 0 ? $max : 1;
        }

        // Hitung perangkingan
        $perangkingan = [];
        foreach ($alternatifs as $alt) {
            $total = 0;
            $row_bobot = ['nik' => $alt->nik];
            foreach ($kriterias as $k) {
                $nilai = Nilai::where('alternatif_nik', $alt->nik)->where('kode_kriteria', $k->kode_kriteria)->first();
                $val = $nilai ? $nilai->value : 0;
                $norm = $val / $maxValues[$k->kode_kriteria];
                $bobot = $norm * $k->weight;
                $row_bobot[$k->kode_kriteria] = round($bobot, 4);
                $total += $bobot;
            }
            $row_bobot['total'] = round($total, 4);
            $perangkingan[] = $row_bobot;
        }

        // Urutkan by total DESC
        usort($perangkingan, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Assign Rank (Rank 1,2,... yang total>0, sisanya '-')
        $curRank = 1;
        foreach ($perangkingan as &$row) {
            $row['rank'] = $row['total'] > 0 ? $curRank++ : '-';
        }
        unset($row);

        // Urut ulang sesuai Rank ASC (angka dulu, baru '-')
        usort($perangkingan, function ($a, $b) {
            if ($a['rank'] == '-' && $b['rank'] == '-') return 0;
            if ($a['rank'] == '-') return 1;
            if ($b['rank'] == '-') return -1;
            return $a['rank'] <=> $b['rank'];
        });

        // Passing ke view
        return view('LihatPerhitungan.cetakPDF', [
            'perangkingan' => $perangkingan,
            'kriterias' => $kriterias,
            'alternatifs' => $alternatifs,
        ]);
    }
}

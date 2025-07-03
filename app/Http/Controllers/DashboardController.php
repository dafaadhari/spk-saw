<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Nilai;
use App\Models\Hasil;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index()
    {
        // Ambil semua data dari tabel 
        $kriteriaCount = Kriteria::count();
        $AlternatifCount = Alternatif::count();
        $penilaianCount = Nilai::count();
        $perhitunganCount = Hasil::count();

        // Group per rank
        $rankCounts = Hasil::with('Alternatif')
            ->orderBy('rank', 'asc')
            ->take(5)
            ->get();


        // Kirim data ke view 'dashboard'
        return view('dashboard', compact(
            'kriteriaCount',
            'AlternatifCount',
            'penilaianCount',
            'perhitunganCount',
            'rankCounts'
        ));
    }
}

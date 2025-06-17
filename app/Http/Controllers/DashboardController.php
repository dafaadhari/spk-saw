<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Tendik;
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
        $tendikCount = Tendik::count();
        $penilaianCount = Nilai::count();
        $perhitunganCount = Hasil::count();

        // Group per rank
    $rankCounts = Hasil::select('rank')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('rank')
        ->orderBy('rank')
        ->get();

        // Kirim data ke view 'dashboard'
        return view('dashboard', compact(
            'kriteriaCount',
            'tendikCount',
            'penilaianCount',
            'perhitunganCount',
            'rankCounts'
        ));
    }
}

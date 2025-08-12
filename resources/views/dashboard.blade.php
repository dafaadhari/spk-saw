@extends('layouts.app')

<title>Dashboard | Sistem Pendukung Keputusan</title>
@section('content')
    <div id="app-content">
        <div class="app-content-area pt-0 ">
            <div class="bg-primary pt-12 pb-21 "></div>
            <div class="container-fluid mt-n22 ">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <!-- Page header -->
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="mb-2 mb-lg-0 ">
                                <h3 class="mb-0" style="color: white">Dashboard</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row card overview -->
                <div class="row">
                    <!-- Card Kriteria -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <a href="{{ url('/kriteria') }}" class="text-decoration-none text-dark">
                            <div class="card h-100 card-lift">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-dark">
                                            <h4 class="mb-0">Kelola Bobot Kriteria</h4>
                                        </div>
                                        <div class="icon-shape icon-md bg-primary-soft text-dark rounded-2">
                                            <i data-feather="sliders" height="20" width="20"></i>
                                        </div>
                                    </div>
                                    <div class="lh-1 text-dark">        
                                        @if ($kriteriaCount > 0)
                                            <h1 class="mb-1 fw-bold">{{ $kriteriaCount }}</h1>
                                            <p class="mb-0">Total Kriteria</p>
                                        @else
                                            <p class="mb-0">Tidak ada data kriteria.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Alternatif -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <a href="{{ url('/Alternatif') }}" class="text-decoration-none text-dark">
                            <div class="card h-100 card-lift">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-dark">
                                            <h4 class="mb-0">Kelola Data Alternatif</h4>
                                        </div>
                                        <div class="icon-shape icon-md bg-primary-soft text-dark rounded-2">
                                            <i data-feather="users" height="20" width="20"></i>
                                        </div>
                                    </div>
                                    <div class="lh-1 text-dark">
                                        @if ($AlternatifCount > 0)
                                            <h1 class="mb-1 fw-bold">{{ $AlternatifCount }}</h1>
                                            <p class="mb-0">Total Pegawai Alternatif</p>
                                        @else
                                            <p class="mb-0">Tidak ada data Alternatif.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Penilaian -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <a href="{{ url('/nilai') }}" class="text-decoration-none text-dark">
                            <div class="card h-100 card-lift">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-dark">
                                            <h4 class="mb-0">Kelola Penilaian</h4>
                                        </div>
                                        <div class="icon-shape icon-md bg-primary-soft text-dark rounded-2">
                                            <i data-feather="check-circle" height="20" width="20"></i>
                                        </div>
                                    </div>
                                    <div class="lh-1">
                                        @if ($penilaianCount > 0)
                                            <h1 class="mb-1 fw-bold text-dark">{{ $penilaianCount }}</h1>
                                            <p class="mb-0 text-dark">Total Data Penilaian</p>
                                        @else
                                            <p class="mb-0 text-dark">Tidak ada data penilaian.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Lihat Perhitungan -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <div class="card h-100 card-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0">
                                            <a href="{{ url('/lihatPerhitungan') }}" class="text-decoration-none text-dark">
                                                Lihat Perhitungan
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-primary-soft text-dark rounded-2">
                                        <i data-feather="bar-chart-2" height="20" width="20"></i>
                                    </div>
                                </div>

                                <div class="lh-1 text-dark">
                                    @if ($perhitunganCount > 0)
                                        <a href="{{ url('/lihatPerhitungan') }}" class="text-decoration-none text-dark">
                                            <h1 class="mb-1 fw-bold">{{ $perhitunganCount }}</h1>
                                            <p class="mb-2 text-dark">Total Hasil Perhitungan</p>
                                        </a>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-2 text-dark">Top 5 Hasil Perhitungan</p>
                                            <button class="btn btn-sm p-0 border-0 bg-transparent text-dark" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#topRankList"
                                                aria-expanded="false" aria-controls="topRankList">
                                                <i data-feather="chevron-down"></i>
                                            </button>
                                        </div>
                                        <div class="collapse" id="topRankList">
                                            @foreach ($rankCounts as $rank)
                                                <div class="d-flex justify-content-between mb-3">
                                                    <div>
                                                        <span class="fw-semibold text-dark">Rank {{ $rank->rank }}</span><br>
                                                        <small class="text-dark">{{ $rank->Alternatif->nama ?? '-' }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-light text-dark">{{ $rank->final_hasil }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mb-0 text-dark">Tidak ada data perhitungan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- penutup row card overview -->

                <!-- Card Spk & SAW -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-5">
                        <div class="card card-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-dark">
                                        <h2 class="mb-0">Sistem Pendukung Keputusan</h2>
                                    </div>
                                </div>
                                <p class="mb-0 text-dark">
                                    Sistem pendukung keputusan (SPK) adalah sistem berbasis komputer yang dibuat untuk membantu para pengambil keputusan manajerial, 
                                    terutama dalam situasi yang membutuhkan penyelidikan dan pertimbangan yang lebih menyeluruh.  
                                    Untuk membuat penilaian yang lebih baik dan berguna, SPK ini mencoba membantu pengambil keputusan 
                                    dalam memilih tindakan terbaik berdasarkan berbagai kriteria terkait, termasuk data, model, dan informasi lainnya. (Prayoga & Utami, 2021).
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-5">
                        <div class="card card-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-dark">
                                        <h2 class="mb-0">Metode Simple Additive Weighting</h2>
                                    </div>
                                </div>
                                <p class="mb-0 text-dark">
                                    Proses pengambilan keputusan yang disebut <em>Simple Additive Weighting</em> (SAW) 
                                    menggunakan sejumlah kriteria sederhana dan tradisional.  
                                    Teknik ini, yang juga dikenal sebagai penjumlahan terbobot, 
                                    menggunakan metodologi pembobotan langsung.  
                                    Tujuan utama metode SAW adalah untuk menentukan peringkat kinerja terbobot setiap alternatif 
                                    secara keseluruhan berdasarkan semua kualitas yang tersedia. (Asminah, 2022).
                                </p>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- Close tag Card Spk & SAW -->

@endsection

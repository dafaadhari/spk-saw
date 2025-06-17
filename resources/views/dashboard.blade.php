@extends('layouts.app')

<title>Dashboard | Sistem Pemgambilan Keputusan</title>
@section('content')
<div id="app-content">
    <div class="app-content-area pt-0 ">
        <div class="bg-primary pt-12 pb-21 "></div>
        <div class="container-fluid mt-n22 ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <!-- Page header -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0  text-white">Dashboard</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                    <!-- card -->
                    <div class="card h-100 card-lift">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- heading -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-0">Kelola Bobot Kriteria</h4>
                                </div>
                                <div class="icon-shape icon-md bg-primary-soft text-primary rounded-2">
                                    <i data-feather="sliders" height="20" width="20"></i>
                                </div>
                            </div>
                            <div class="lh-1">
                                @if ($kriteriaCount > 0)
                                <h1 class="mb-1 fw-bold">{{ $kriteriaCount }}</h1>
                                <p class="mb-0">Total Kriteria</p>
                                @else
                                <p class="mb-0">Tidak ada data kriteria.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                    <!-- card -->
                    <div class="card h-100 card-lift">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- heading -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-0">Kelola Data Tendik</h4>
                                </div>
                                <div class="icon-shape icon-md bg-primary-soft text-primary rounded-2">
                                    <i data-feather="users" height="20" width="20"></i>
                                </div>
                            </div>
                            <!-- content -->
                            <div class="lh-1">
                                @if ($tendikCount > 0)
                                <h1 class="mb-1 fw-bold">{{ $tendikCount }}</h1>
                                <p class="mb-0">Total Pegawai Tendik</p>
                                @else
                                <p class="mb-0">Tidak ada data tendik.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                    <!-- card -->
                    <div class="card h-100 card-lift">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- heading -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-0">Kelola Penilaian</h4>
                                </div>
                                <div class="icon-shape icon-md bg-primary-soft text-primary rounded-2">
                                    <i data-feather="check-circle" height="20" width="20"></i>
                                </div>
                            </div>
                            <div class="lh-1">
                                @if ($penilaianCount > 0)
                                <h1 class="mb-1 fw-bold">{{ $penilaianCount }}</h1>
                                <p class="mb-0">Total Data Penilaian</p>
                                @else
                                <p class="mb-0">Tidak ada data penilaian.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                    <!-- card -->
                    <div class="card h-100 card-lift">
                        <!-- card body -->
                        <div class="card-body">
                            <!-- heading -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-0">Lihat Perhitungan</h4>
                                </div>
                                <div class="icon-shape icon-md bg-primary-soft text-primary rounded-2">
                                    <i data-feather="bar-chart-2" height="20" width="20"></i>
                                </div>
                            </div>
                            <div class="lh-1">
                                @if ($perhitunganCount > 0)
                                <h1 class="mb-1 fw-bold">{{ $perhitunganCount }}</h1>
                                <p class="mb-2">Total Hasil Perhitungan</p>

                                <div>
                                    @foreach ($rankCounts as $rank)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Rank {{ $rank->rank }}</span>
                                        <span>{{ $rank->total }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="mb-0">Tidak ada data perhitungan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
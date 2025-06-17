@extends('layouts.app')

<title>Lihat Perhitungan | Sistem Pemgambilan Keputusan</title>
@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="mb-2 mb-lg-0">
                            <h3 class="mb-0 text-white">Hasil Perhitungan SAW</h3>
                        </div>
                        <div>
                            <a href="/lihatPerhitungan/cetakPDF" target="_blank"
                                class="btn  bg-secondary text-light font-semibold shadow-sm">
                                Cetak PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil SAW -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">ID Tendik</th>
                                    <th scope="col">Nama Tendik</th>
                                    <th scope="col">Nilai SAW</th>
                                    <th scope="col">Ranking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ 'T' . str_pad($row['tendik_id'], 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $row['nama'] }}</td>
                                    <td>{{ number_format($row['nilai_akhir'], 4) }}</td>
                                    <td>{{ $row['rank'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
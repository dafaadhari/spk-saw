@extends('layouts.app')

<title>Lihat Perhitungan | Sistem Pendukung Keputusan</title>
@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="text-white mb-0">Hasil Perhitungan SAW</h3>
                        <a href="/lihatPerhitungan/cetakPDF" target="_blank" class="btn bg-white text-dark shadow-sm">
                            Cetak PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning fw-bold text-center mb-4">
                Highlight:
                <span class="badge bg-success">Hijau = Top 4</span>
                <span class="badge bg-info text-dark">Biru = Lolos Non Top 4</span>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIK Tendik</th>
                                    <th>Nama Tendik</th>
                                    <th>Nilai SAW</th>
                                    <th>Ranking</th>
                                    <th>Jam Lembur Bulanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $index => $row)
                                @php
                                $warna = '';
                                if ($row['jam_kerja_bulanan'] < 160) {
                                    $warna='bg-info text-dark' ;
                                    } elseif ($row['rank'] <=4) {
                                    $warna='bg-success text-white' ;
                                    } else {
                                    $warna='bg-info  text-dark' ;
                                    }
                                    @endphp
                                    <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="{{$warna}}">{{ $row['tendik_nik'] }}</td>
                                    <td class="{{ $warna }}">{{ $row['nama'] }}</td>
                                    <td class="{{$warna}}">{{ number_format($row['nilai_akhir'], 4) }}</td>
                                    <td class="{{ $warna }}">{{ $row['rank'] }}</td>
                                    <td class="{{ $warna }}">{{ $row['jam_kerja_bulanan'] }}</td>
                                    </tr>
                                    @endforeach
                                    @if (count($hasil) === 0)
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
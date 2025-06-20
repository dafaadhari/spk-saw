@extends('layouts.app')

<title>Data Tereliminasi | Sistem Pendukung Keputusan</title>
@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-white mb-0">Data Tereliminasi</h3>
                        <a href="/lihatPerhitungan/cetakEliminasiPDF" target="_blank" class="btn btn-white text-dark">
                            Cetak PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
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
                                @forelse ($hasil as $index => $row)
                                <tr class="bg-warning text-dark">
                                    <td class="bg-warning text-dark">{{ $index + 1 }}</td>
                                    <td class="bg-warning text-dark">{{ $row['tendik_nik'] }}</td>
                                    <td class="bg-warning text-dark">{{ $row['nama'] }}</td>
                                    <td class="bg-warning text-dark">{{ number_format($row['nilai_akhir'], 4) }}</td>
                                    <td class="bg-warning text-dark">{{ $row['rank'] }}</td>
                                    <td class="bg-warning text-dark">{{ $row['jam_kerja_bulanan'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data tendik tereliminasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

<title>Lihat Perhitungan | Sistem Pendukung Keputusan</title>
@section('content')
<div id="app-content">
    <div class="app-content-area pt-0">
        <div class="bg-primary pt-12 pb-21"></div>
        <div class="container-fluid mt-n22">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 style="color:white">Hasil Perhitungan SAW</h3>
                <a href="/lihatPerhitungan/cetakPDF" target="_blank" class="btn bg-white text-dark shadow-sm">Cetak PDF</a>
            </div>
            <form method="GET" action="/lihatPerhitungan" class="row mb-3 align-items-center">
                <div class="col-md-8 mb-2">
                    <label class="d-flex align-items-center gap-2 flex-wrap text-white">
                        Show
                        <select name="entries" class="form-select d-inline w-auto">
                            @foreach([5, 10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ $entries == $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-4 d-flex">
                    <input type="text" name="search" class="form-control me-2" value="{{ $search }}" placeholder="Cari...">
                    <button type="submit" class="btn btn-light">Cari</button>
                </div>
            </form>
            <div class="alert alert-warning fw-bold text-center">
                <span class="badge bg-success">Hijau = Top 4</span>
                <span class="badge bg-primary">Biru = Lolos</span>
                <span class="badge bg-warning text-dark">Kuning = Tereliminasi</span>
            </div>

            <!-- Filter Form -->


            <!-- Lolos -->
            <h4 class="text-success">Lolos</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Nilai SAW</th>
                            <th>Ranking</th>    
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lolos as $index => $row)
                        @php $warna = $row['rank'] <= 4 ? 'bg-success text-white' : 'bg-primary text-white' ; @endphp
                            <tr>
                            <td>{{ $lolos->firstItem() + $index }}</td>
                            <td class="{{ $warna }}">{{ $row['tendik_nik'] }}</td>
                            <td class="{{ $warna }}">{{ $row['nama'] }}</td>
                            <td class="{{ $warna }}">{{ number_format($row['nilai_akhir'], 4) }}</td>
                            <td class="{{ $warna }}">{{ $row['rank'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data lolos.</td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
                {{-- {{ $lolos->appends(request()->query())->links() }} --}}
            </div>

            <!-- Eliminasi -->
            <h4 class="text-warning">Tereliminasi</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Nilai SAW</th>
                            <th>Ranking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eliminasi as $index => $row)
                        <tr>
                            <td>{{ $eliminasi->firstItem() + $index }}</td>
                            <td class="bg-warning text-dark">{{ $row['tendik_nik'] }}</td>
                            <td class="bg-warning text-dark">{{ $row['nama'] }}</td>
                            <td class="bg-warning text-dark">{{ number_format($row['nilai_akhir'], 4) }}</td>
                            <td class="bg-warning text-dark">{{ $row['rank'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data tereliminasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- {{ $eliminasi->appends(request()->query())->links() }} --}}
            </div>

        </div>
    </div>
</div>
@endsection
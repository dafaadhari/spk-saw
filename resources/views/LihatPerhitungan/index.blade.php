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

                </div>
                <div class="col-md-4 d-flex">
                    <input type="text" name="search" class="form-control me-2" value="{{ $search }}" placeholder="Cari...">
                    <button type="submit" class="btn btn-light">Cari</button>
                </div>
            </form>
            <!-- <div class="alert alert-warning fw-bold text-center">
                <span class="badge bg-success">Hijau = Top 4</span>
                <span class="badge bg-primary">Biru = Lolos</span>
                <span class="badge bg-warning text-dark">Kuning = Tereliminasi</span>
            </div> -->
            <div style="margin-top: 50px;"></div>
            <!-- Tabel 1: Hasil Analisa Kualitatif -->
            <h4 class="mt-4">Tabel Hasil Analisa (Kualitatif)</h4>
            <div class="table-responsive mb-4">
                <table id="tabelKualitatif" class="table table-bordered align-middle display">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama</th>
                            @foreach ($kriterias as $k)
                            <th>{{ $k->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($analisa_kualitatif as $row)
                        <tr>
                            <td>{{ $row['nama'] }}</td>
                            @foreach ($kriterias as $k)
                            <td>{{ $row[$k->kode_kriteria] ?? '-' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mb-4">
                <table id="tabelSkoring" class="table table-bordered align-middle display">
                    <thead class="table-light">
                        <tr>
                            <th>NIK</th>
                            @foreach ($kriterias as $k)
                            <th>{{ $k->kode_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skoring_kuantitatif as $row)
                        <tr>
                            <td>{{ $row['nik'] }}</td>
                            @foreach ($kriterias as $k)
                            <td>{{ $row[$k->kode_kriteria] ?? 0 }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel Normalisasi -->
            <h4 class="mt-4">Tabel Normalisasi</h4>
            <div class="table-responsive mb-4">
                <table id="normalisasiTable" class="table table-bordered display">
                    <thead class="table-primary">
                        <tr>
                            <th>Kode Alternatif</th>
                            @foreach ($kriterias as $k)
                            <th>{{ $k->kode_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($normalisasi as $row)
                        <tr>
                            <td>{{ $row['nik'] }}</td>
                            @foreach ($kriterias as $k)
                            <td>{{ $row[$k->kode_kriteria] ?? 0 }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Tabel Perangkingan -->
            <h4 class="mt-4">Tabel Perangkingan</h4>
            <div class="table-responsive mb-4">
                <table id="perangkinganTable" class="table table-bordered display">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach ($kriterias as $k)
                            <th>{{ $k->nama }}</th>
                            @endforeach
                            <th>Total</th>
                            <th>Rank</th>
                        </tr>
                        <tr>
                            <th>Bobot</th>
                            @foreach ($kriterias as $k)
                            <th>{{ $k->weight }}</th>
                            @endforeach
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perangkingan as $row)
                        <tr>
                            <td>{{ $alternatifs->where('nik', $row['nik'])->first()->nama ?? $row['nik'] }}</td>
                            @foreach ($kriterias as $k)
                            <td>{{ $row[$k->kode_kriteria] ?? 0 }}</td>
                            @endforeach
                            <td><strong>{{ $row['total'] }}</strong></td>
                            <td>{{ $row['rank'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table1 = $('#tabelKualitatif').DataTable({
            lengthMenu: [5, 10, 25, 50, 100],
        }); // Ini yang aktif filter & pagination
        var table2 = $('#tabelSkoring').DataTable({
            lengthMenu: [5, 10, 25, 50, 100],
            paging: false, // Nonaktifkan paging di sini
            searching: false, // Nonaktifkan search di sini
            info: false
        });

        // Sinkronisasi pagination dan jumlah entries (jika mau)
        function syncSkoringTable() {
            var pageInfo = table1.page.info();
            var start = pageInfo.start;
            var end = pageInfo.end;

            var table2FilteredData = table2.rows({
                search: 'applied'
            }).data().toArray();
            var slicedData = table2FilteredData.slice(start, end);

            var tbody = $('#tabelSkoring tbody');
            tbody.empty();
            slicedData.forEach(function(row) {
                var tr = '<tr>';
                row.forEach(function(cell) {
                    tr += '<td>' + cell + '</td>';
                });
                tr += '</tr>';
                tbody.append(tr);
            });
        }

        table1.on('draw', syncSkoringTable);
        syncSkoringTable(); // <-- panggil langsung di awal

        $('#normalisasiTable').DataTable({
            lengthMenu: [5, 10, 25, 50, 100]
        });
        $('#perangkinganTable').DataTable({
            lengthMenu: [5, 10, 25, 50, 100],
        });
        $('#lolosTable').DataTable({
            lengthMenu: [5, 10, 25, 50, 100],
        });
        $('#eliminasiTable').DataTable({
            lengthMenu: [5, 10, 25, 50, 100],
        });
    });
</script>
@endsection
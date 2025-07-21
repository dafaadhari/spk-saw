<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Perhitungan SAW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {

            .table th,
            .table td {
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
            }

            .table-primary {
                background-color: #b6d4fe !important;
                color: #222 !important;
            }

            .table-success {
                background-color: #d1e7dd !important;
            }
        }

        .table-primary {
            background-color: #b6d4fe !important;
        }

        .table-success {
            background-color: #d1e7dd !important;
        }
    </style>
</head>

<body class="text-dark bg-white p-4">

    <h2 class="text-center fw-bold mb-4">Laporan Hasil Perhitungan SAW</h2>

    <h5 class="fw-bold">Tabel Perangkingan Alternatif</h5>
    <table class="table table-bordered align-middle text-center table-sm">
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
                <tr @if ($row['rank'] !== '-' && $row['rank'] <= 4) class="table" @endif>
                    <td>{{ $alternatifs->where('nik', $row['nik'])->first()->nama ?? $row['nik'] }}</td>
                    @foreach ($kriterias as $k)
                        <td>{{ $row[$k->kode_kriteria] ?? 0 }}</td>
                    @endforeach
                    <td><strong>{{ $row['total'] }}</strong></td>
                    <td>{{ $row['rank'] }}</td>
                </tr>
            @endforeach
            @if (count($perangkingan) === 0)
                <tr>
                    <td colspan="{{ count($kriterias) + 3 }}" class="text-center">Tidak ada data.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>

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

            .bg-success {
                background-color: #198754 !important;
                color: white !important;
            }

            .bg-warning {
                background-color: #ffc107 !important;
                color: black !important;
            }

            .bg-primary {
                background-color: #0d6efd !important;
                color: white !important;
            }
        }
    </style>
</head>

<body class="text-dark bg-white p-4">

    <h2 class="text-center fw-bold mb-4">Laporan Hasil Perhitungan SAW</h2>

    <!-- Tabel Lolos -->
    <h5 class="fw-bold">Alternatif Lolos </h5>
    <table class="table table-bordered align-middle text-center mb-5 table-sm">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>NIK Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Nilai SAW</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lolos as $index => $row)
            @php
            $rowClass = $row['rank'] <= 4 ? 'bg-success' : 'bg-primary' ;
                @endphp
                <tr class="{{ $rowClass }}">
                <td class="{{$rowClass}}">{{ $index + 1 }}</td>
                <td class="{{$rowClass}}">{{ $row['alternatif_nik'] }}</td>
                <td class="{{$rowClass}}">{{ $row['nama'] }}</td>
                <td class="{{$rowClass}}">{{ number_format($row['nilai_akhir'], 4) }}</td>
                <td class="{{$rowClass}}">{{ $row['rank'] }}</td>
                </tr>
                @endforeach
                @if (count($lolos) === 0)
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data.</td>
                </tr>
                @endif
        </tbody>
    </table>

    <!-- Tabel Tereliminasi -->
    <h5 class="fw-bold">Alternatif Tereliminasi </h5>
    <table class="table table-bordered align-middle text-center table-sm">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>NIK Alternatif</th>
                <th>Nama Alternatif</th>
                <th>Nilai SAW</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eliminasi as $index => $row)
            <tr class="bg-warning">
                <td class="bg-warning">{{ $index + 1 }}</td>
                <td class="bg-warning">{{ $row['alternatif_nik'] }}</td>
                <td class="bg-warning">{{ $row['nama'] }}</td>
                <td class="bg-warning">{{ number_format($row['nilai_akhir'], 4) }}</td>
                <td class="bg-warning">{{ $row['rank'] }}</td>
            </tr>
            @endforeach
            @if (count($eliminasi) === 0)
            <tr>
                <td colspan="5" class="text-center">Tidak ada data.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>
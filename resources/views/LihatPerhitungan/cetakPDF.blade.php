<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Perhitungan SAW</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {

            .table th,
            .table td {
                border: 1px solid #dee2e6 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="text-dark bg-white">

    <div class=" ">
        <h2 class="text-center fw-bold mb-4">Laporan Hasil Perhitungan SAW</h2>

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
                @foreach ($hasil as $index => $row)
                @php
                $rowClass = '';
                if ($row['jam_kerja_bulanan'] < 160) {
                    $rowClass='bg-info text-dark' ;
                    } elseif ($row['rank'] <=4) {
                    $rowClass='bg-success text-light' ;
                    } else {
                    $rowClass='bg-info text-text-dark' ;
                    }
                    @endphp
                    <tr class="{{ $rowClass }}">
                    <td class="{{$rowClass}}">{{ $index + 1 }}</td>
                    <td class="{{$rowClass}}">{{ $row['tendik_nik'] }}</td>
                    <td class="{{$rowClass}}">{{ $row['nama'] }}</td>
                    <td class="{{$rowClass}}">{{ number_format($row['nilai_akhir'], 4) }}</td>
                    <td class="{{$rowClass}}">{{ $row['rank'] }}</td>
                    <td class="{{$rowClass}}">{{ $row['jam_kerja_bulanan'] }}</td>
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

    <script>
        window.print();
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Tereliminasi SAW</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table th,
            .table td {
                border: 1px solid #dee2e6 !important;
            }

            .table-warning {
                background-color: #fff3cd !important;
                color: #000 !important;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-white text-dark">

    <div class=" py-4">
        <h2 class="text-center fw-bold mb-4">Laporan Tendik Tereliminasi</h2>

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
                @php $no = 1; @endphp
                @forelse ($hasil as $row)
                    @if ($row['jam_kerja_bulanan'] < 160)
                        <tr class="bg-warning text-dark">
                            <td class="bg-warning text-dark">{{ $no++ }}</td>
                            <td class="bg-warning text-dark">{{ $row['tendik_nik'] }}</td>
                            <td class="bg-warning text-dark">{{ $row['nama'] }}</td>
                            <td class="bg-warning text-dark">{{ number_format($row['nilai_akhir'], 4) }}</td>
                            <td class="bg-warning text-dark">{{ $row['rank'] }}</td>
                            <td class="bg-warning text-dark">{{ $row['jam_kerja_bulanan'] }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data tereliminasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>

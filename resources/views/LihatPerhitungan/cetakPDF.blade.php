<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Perhitungan SAW</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="text-sm font-sans leading-normal text-gray-900 bg-white">

    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4 text-center">Laporan Hasil Perhitungan SAW</h2>

        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border border-gray-300 px-2 py-1">No</th>
                    <th class="border border-gray-300 px-2 py-1">ID Tendik</th>
                    <th class="border border-gray-300 px-2 py-1">Nama Tendik</th>
                    <th class="border border-gray-300 px-2 py-1">Nilai SAW</th>
                    <th class="border border-gray-300 px-2 py-1">Ranking</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasil as $index => $row)
                <tr>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ 'T' . str_pad($row['tendik_id'], 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $row['nama'] }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ number_format($row['nilai_akhir'], 4) }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $row['rank'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="mt-6 text-sm">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>

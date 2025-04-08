<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1,
        .header h2,
        .header h3 {
            margin: 5px 0;
        }

        .timestamp {
            text-align: right;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        td.name {
            text-align: left;
            padding-left: 15px;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $header }}</h1>
        <h2>{{ $title }}</h2>
        <h3>Bulan {{ $monthName }}, Tahun {{ $tahun }}</h3>
    </div>
    <div class="timestamp">
        <p>Dibuat pada: {{ $currentTimestamp }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jumlah Hadir</th>
                <th>Jumlah Izin</th>
                <th>Jumlah Alpha</th>
                <th>Jumlah Terlambat</th>
                <th>Total Jam Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="name">{{ $item->nama_karyawan }}</td>
                    <td>{{ $item->jumlah_hadir }}</td>
                    <td>{{ $item->jumlah_izin }}</td>
                    <td>{{ $item->jumlah_alpha }}</td>
                    <td>{{ $item->total_telat }}</td>
                    <td>{{ $item->total_lembur }} jam</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

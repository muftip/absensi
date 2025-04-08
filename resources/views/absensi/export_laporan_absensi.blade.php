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

        .header h1 {
            margin: 5px 0;
            font-size: 18pt;
            /* Perbesar font */
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
            /* Perbesar font */
        }

        .header h3 {
            margin: 5px 0;
            font-size: 16pt;
            /* Perbesar font */
        }

        .timestamp {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12pt;
            /* Perbesar font */
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
            background-color: #efefef;
            /* Warna abu-abu lebih terang */
            padding: 10px;
            text-align: center;
            color: #000;
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
        <p>Dibuat pada: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 15%;">Jumlah Hadir</th>
                <th style="width: 15%;">Jumlah Izin</th>
                <th style="width: 15%;">Jumlah Alpha</th>
                <th style="width: 15%;">Jumlah Terlambat</th>
                <th style="width: 15%;">Total Jam Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="name">{{ $item->nama_karyawan }}</td>
                    <td>{{ $item->jumlah_hadir }}</td>
                    <td>{{ $item->jumlah_izin }}</td>
                    <td>{{ $item->jumlah_alpha }}</td>
                    <td>{{ $item->total_telat }}</td>
                    <td>{{ $item->total_lembur }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

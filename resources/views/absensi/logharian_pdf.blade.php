<!DOCTYPE html>
<html>

<head>
    <title>Log Harian {{ $nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <center>
        <h1 style="margin:0;">CV. ANUGRAH ABADI</h1>
    </center>
    <center>
        <h2> Log Harian {{ $nama }}</h2>
    </center>
    <br>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinedLogs as $log)
                <tr
                    style="{{ isset($log['type']) && $log['type'] == 'alpha' ? 'background-color: #f8d7da;' : (isset($log['type']) && $log['type'] == 'izin' ? 'background-color: #d6d8db;' : '') }}">
                    <td>{{ $log['tanggal'] }}</td>
                    <td>{{ isset($log['waktu_masuk']) ? $log['waktu_masuk'] : '-' }}</td>
                    <td>{{ isset($log['waktu_keluar']) ? $log['waktu_keluar'] : '-' }}</td>
                    <td>
                        @if (isset($log['keterangan_izin']))
                            {{ $log['keterangan_izin'] }}
                        @elseif(isset($log['keterangan_libur']))
                            {{ $log['keterangan_libur'] }}
                        @elseif(isset($log['type']) && $log['type'] == 'alpha')
                            Alpha
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

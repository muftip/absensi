@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex mb-3">
            <div class="card-title fw-semibold flex-grow-1">Riwayat Aktivitas</div>
        </div>
        <table class="display" id="activityTable">
            <thead>
                <tr>
                    <th scope="col">Tanggal & Waktu</th>
                    <th scope="col">Nama karyawan</th>
                    <th scope="col">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity["created_at"]->format('d M Y, H:i:s') }} WIB</td>
                        <td>{{ $activity["nama"] }}</td>
                        <td class="@if($activity['status'] === 'Menghapus') text-danger @elseif($activity['status'] === 'Memperbarui') text-primary @elseif($activity['status'] === 'Membuat') text-success @endif">
                            {{ $activity["deskripsi"] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#activityTable').DataTable({
            "order": [
                    [0, 'desc']
            ]
        });
    });
</script>
@endsection

@section('extra_styles')
<style>
    #activityTable thead th {
        text-align: center;
    }
    #activityTable tbody td {
        text-align: center;
    }
    #activityTable tbody td:nth-child(4) {
        text-align: left;
    }
    .text-danger {
        color: red;
    }
    .text-primary {
        color: blue;
    }
    .text-success {
        color: green;
    }
</style>
@endsection

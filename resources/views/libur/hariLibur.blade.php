@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h5 class="card-title fw-semibold" style="margin-bottom: 0">Data Hari Libur</h5>
                </div>
                <a href="{{ route('hari-libur.create') }}" class="btn btn-primary">Tambah</a>
            </div>
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Tanggal Mulai Libur</th>
                        <th>Tanggal Selesai Libur</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($harilibur as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_selesai)->translatedFormat('d F Y') }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>
                                <form method="POST" action="{{ route('hari-libur.destroy', $row->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger fs-1 hapus_hari-libur"
                                        data-toggle="tooltip" title='Delete'
                                        data-nama='{{ $row->keterangan }}'>Hapus</button>
                                    <a href="{{ route('hari-libur.edit', $row->id) }}" class="btn btn-primary fs-1">Ubah</a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection

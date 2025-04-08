@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h5 class="card-title fw-semibold" style="margin-bottom: 0">Data Jabatan</h5>
                </div>
                <a href="{{ route('jabatan.create') }}" class="btn btn-primary">Tambah</a>
            </div>
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Id Jabatan</th>
                        <th>Nama Jabatan</th>
                        @if (session('hak_akses') !== 'Admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jabatan as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nama }}</td>
                            @if (session('hak_akses') !== 'Admin')
                                <td>
                                    <form method="POST" action="{{ route('jabatan.destroy', $row->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger fs-1 hapus_jabatan"
                                            data-toggle="tooltip" title='Delete'
                                            data-nama='{{ $row->nama }}'>Hapus</button>
                                        <a href="{{ route('jabatan.edit', $row->id) }}"
                                            class="btn btn-primary fs-1">Ubah</a>
                                    </form>
                                </td>
                            @endif
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

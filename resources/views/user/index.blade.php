@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h5 class="card-title fw-semibold" style="margin-bottom: 0">Data User</h5>
                </div>

                @if ($allKaryawanHaveAccess || count($karyawan) == 0)
                    <button class="btn btn-primary" disabled>Tambah</button>
                    <div class="alert alert-warning">
                        @if (count($karyawan) == 0)
                            Tidak ada data karyawan, harap tambahkan data karyawan dahulu!
                        @else
                            Semua karyawan sudah memiliki hak akses!
                        @endif
                    </div>
                @else
                    <a href="{{ url('users/create') }}" class="btn btn-primary">Tambah</a>
                @endif
            </div>

            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Karyawan</th>
                        <th>Hak Akses User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $row)
                        <tr>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->hak_akses }}</td>
                            <td>
                                <form method="POST" action="{{ route('users.destroy', $row->id_karyawan) }}">
                                    @csrf
                                    @method('delete')

                                    @if (session('hak_akses') === 'Admin')
                                        <!-- Hide "Hapus" button for Admin -->
                                        <button type="button" class="btn btn-danger fs-1"
                                            style="display: none;">Hapus</button>

                                        <!-- Hide "Ubah" button if the hak_akses is Director -->
                                        @if ($row->hak_akses === 'Director')
                                            <a href="#" class="btn btn-primary fs-1" style="display: none;">Ubah</a>
                                        @else
                                            <a href="{{ route('users.edit', $row->id_karyawan) }}"
                                                class="btn btn-primary fs-1">Ubah</a>
                                        @endif
                                    @else
                                        <!-- Non-Admin users can perform all actions -->
                                        <button type="submit" class="btn btn-danger fs-1 hapus_user" data-toggle="tooltip"
                                            title='Delete' data-nama='{{ $row->nama }}'>Hapus</button>
                                        <a href="{{ route('users.edit', $row->id_karyawan) }}"
                                            class="btn btn-primary fs-1">Ubah</a>
                                    @endif
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

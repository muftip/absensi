@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h5 class="card-title fw-semibold" style="margin-bottom: 0">Data Karyawan</h5>
                </div>

                @if (count($jabatan) == 0)
                    <button class="btn btn-primary" disabled>Tambah</button>
                    <div class="alert alert-warning">
                        Tidak ada data jabatan, harap tambahkan data jabatan dahulu!
                    </div>
                @else
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah</a>
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
                        <th>Id Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawan as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->nama_jabatan }}</td>
                            <td>{{ $row->alamat }}</td>
                            <td>{{ $row->no_telp }}</td>
                            <td>
                                <form method="POST" action="{{ route('karyawan.destroy', $row->id) }}">
                                    @csrf
                                    @method('delete')

                                    @if (session('hak_akses') === 'Admin')
                                        @if ($row->nama_jabatan === 'Director')
                                            <!-- Hide "Ubah" and "Hapus" buttons for Director jabatan -->
                                            <!-- No button to show for Director jabatan -->
                                        @else
                                            <!-- Hide "Hapus" button for Admin, but show "Ubah" for other jabatan -->
                                            <button type="button" class="btn btn-danger fs-1"
                                                style="display: none;">Hapus</button>
                                            <a href="{{ route('karyawan.edit', $row->id) }}"
                                                class="btn btn-primary fs-1">Ubah</a>
                                        @endif
                                    @elseif (session('hak_akses') === 'Director')
                                        <!-- Director can perform all actions -->
                                        <button type="submit" class="btn btn-danger fs-1 hapus_karyawan"
                                            data-toggle="tooltip" title='Delete'
                                            data-nama='{{ $row->nama }}'>Non-Aktif</button>
                                        <a href="{{ route('karyawan.edit', $row->id) }}"
                                            class="btn btn-primary fs-1">Ubah</a>
                                    @endif

                                    <a href="{{ route('karyawan.show', $row->id) }}"
                                        class="btn btn-warning fs-1">Detail</a>
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

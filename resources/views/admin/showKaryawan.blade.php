@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h5 class="card-title fw-semibold" style="margin-bottom: 0">Biodata {{ $karyawan->nama }} </h5>
                </div>
                <a href="{{ url('karyawan') }}" class="btn btn-success">Kembali</a>
            </div>

            @if ($karyawan->foto != '')
                <img src="{{ asset('storage/' . $karyawan->foto) }}" width="180" height="240" />
            @endif

            <div style="margin-top: 30px;"></div>

            <table class="table">
                <tr>
                    <td style=padding-left:50px;">Nama Karyawan</td>
                    <td>:</td>
                    <td>{{ $karyawan->nama }}</td>
                </tr>


                <tr>
                    <td style=padding-left:50px;">Id Karyawan</td>
                    <td>:</td>
                    <td>{{ $karyawan->id }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Nama Jabatan</td>
                    <td>:</td>
                    <td>{{ $karyawan->nama_jabatan }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Email</td>
                    <td>:</td>
                    <td>{{ $karyawan->email }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $karyawan->jenis_kelamin }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $karyawan->tempat_lahir }},
                        {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Alamat</td>
                    <td>:</td>
                    <td>{{ $karyawan->alamat }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Agama</td>
                    <td>:</td>
                    <td>{{ $karyawan->agama }}</td>
                </tr>

                <tr>
                    <td style=padding-left:50px;">Nomor Telepon</td>
                    <td>:</td>
                    <td>{{ $karyawan->no_telp }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection

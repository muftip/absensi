@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Ubah Data Jabatan</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('jabatan.update', $jabatan->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="id_jabatan" class="form-label">Id Jabatan</label>
                                <input type="text" class="form-control" id="id" name="id" required
                                    value="{{ $jabatan->id }}">
                                @error('id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nama" class="form-label">Nama Jabatan</label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                    value="{{ $jabatan->nama }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

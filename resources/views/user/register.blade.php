@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tambah Username</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="mb-4" id="fieldset">
                                <label for="id_karyawan" class="form-label">Id Karyawan</label>
                                <select id="id_karyawan" name="id_karyawan" class="form-select"
                                    {{ $users->isEmpty() ? 'disabled' : '' }}>
                                    @forelse ($users as $row)
                                        <option value="{{ $row->id }}">{{ $row->id }} - {{ $row->nama }}
                                        </option>
                                    @empty
                                        <option value="" disabled>-- Tidak ada data karyawan --</option>
                                    @endforelse
                                </select>
                                @error('id_karyawan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    value="{{ old('username') }}">
                                @error('username')
                                    <label for="username" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Hak Akses</label>
                                <div class="form-check">
                                    <input type="radio" id="general_manager" name="hak_akses" class="form-check-input"
                                        value="General Manager" @if (old('hak_akses') == 'General Manager' || old('hak_akses') === null) checked @endif>
                                    <label class="form-check-label" for="general_manager">General Manager</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="admin" name="hak_akses" class="form-check-input"
                                        value="Admin" @if (old('hak_akses') == 'Admin') checked @endif>
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>
                                @if (!$hasDirectorAccess)
                                    <div class="form-check">
                                        <input type="radio" id="director" name="hak_akses" class="form-check-input"
                                            value="Director" @if (old('hak_akses') == 'Director') checked @endif>
                                        <label class="form-check-label" for="director">Director</label>
                                    </div>
                                @endif
                                @error('hak_akses')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    value="{{ old('password') }}">
                                @error('password')
                                    <label for="password" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"
                                    {{ $users->isEmpty() ? 'disabled' : '' }}>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

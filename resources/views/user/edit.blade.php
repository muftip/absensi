@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Ubah Username</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $users->id_karyawan) }}">
                            @csrf
                            @method('PATCH')

                            <!-- id_karyawan Field (disabled) -->
                            <div class="mb-4">
                                <label for="id_karyawan" class="form-label">Id Karyawan</label>
                                <input type="text" class="form-control" id="id_karyawan" name="id_karyawan" required
                                    value="{{ $users->id_karyawan }}" disabled>
                                @error('id_karyawan')
                                    <label for="id_karyawan" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>

                            <!-- username Field -->
                            <div class="mb-4">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    value="{{ old('username', $users->username) }}">
                                @error('username')
                                    <label for="username" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>

                            <!-- hak_akses Field -->
                            <div class="mb-4">
                                <label class="form-label">Hak Akses</label>

                                @php
                                    $hasDirectorAccess = DB::table('users')->where('hak_akses', 'Director')->exists();
                                    $isCurrentUserDirector = $users->hak_akses === 'Director';
                                @endphp

                                <div class="form-check">
                                    <input type="radio" id="general-manager" name="hak_akses" class="form-check-input"
                                        value="General Manager" @if (old('hak_akses', $users->hak_akses) == 'General Manager') checked @endif
                                        @if ($isCurrentUserDirector) disabled @endif>
                                    <label class="form-check-label" for="general-manager">General Manager</label>
                                </div>

                                <div class="form-check">
                                    <input type="radio" id="admin" name="hak_akses" class="form-check-input"
                                        value="Admin" @if (old('hak_akses', $users->hak_akses) == 'Admin') checked @endif
                                        @if ($isCurrentUserDirector) disabled @endif>
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>

                                @if (!$hasDirectorAccess || $isCurrentUserDirector)
                                    <div class="form-check">
                                        <input type="radio" id="director" name="hak_akses" class="form-check-input"
                                            value="Director" @if (old('hak_akses', $users->hak_akses) == 'Director') checked @endif
                                            @if ($isCurrentUserDirector) disabled @endif>
                                        <label class="form-check-label" for="director">Director</label>
                                    </div>
                                @endif

                                @error('hak_akses')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($isCurrentUserDirector)
                                    <input type="hidden" name="hak_akses" value="{{ $users->hak_akses }}">
                                @endif
                            </div>

                            <!-- password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    value="{{ old('password', $users->password) }}">
                                @error('password')
                                    <label for="password" class="text-danger">{{ $message }}</label>
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

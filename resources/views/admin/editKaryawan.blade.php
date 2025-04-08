@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Ubah Data Karyawan</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('karyawan.update', $karyawan->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="id" class="form-label">Id Karyawan</label>
                                <input type="text" class="form-control" id="id" name="id" required
                                    value="{{ $karyawan->id }}" readonly>
                                @error('id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nama" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                    value="{{ old('nama', $karyawan->nama) }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <div class="form-check">
                                    <input type="radio" id="laki-laki" name="jenis_kelamin" class="form-check-input"
                                        value="Laki-laki" @if (old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki') checked @endif>
                                    <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="perempuan" name="jenis_kelamin" class="form-check-input"
                                        value="Perempuan" @if (old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan') checked @endif>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="agama">Agama</label>
                                <select class="form-select" name="agama">
                                    <option value="Islam" @if (old('agama', $karyawan->agama) == 'Islam') selected @endif>Islam</option>
                                    <option value="Katolik" @if (old('agama', $karyawan->agama) == 'Katolik') selected @endif>Katolik
                                    </option>
                                    <option value="Hindu" @if (old('agama', $karyawan->agama) == 'Hindu') selected @endif>Hindu</option>
                                    <option value="Kristen" @if (old('agama', $karyawan->agama) == 'Kristen') selected @endif>Kristen
                                    </option>
                                    <option value="Buddha" @if (old('agama', $karyawan->agama) == 'Buddha') selected @endif>Buddha</option>
                                    <option value="Konghucu" @if (old('agama', $karyawan->agama) == 'Konghucu') selected @endif>Konghucu
                                    </option>
                                </select>
                                @error('agama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ old('email', $karyawan->email) }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="id_jabatan" class="form-label">Jabatan</label>
                                <select id="id_jabatan" name="id_jabatan" class="form-select"
                                    @if ($isCurrentDirector) disabled @endif>
                                    @foreach ($jabatanOptions as $kodeJabatan => $namaJabatan)
                                        <option value="{{ $kodeJabatan }}"
                                            @if (old('id_jabatan', $karyawan->id_jabatan) == $kodeJabatan) selected @endif>
                                            {{ $namaJabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jabatan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <!-- Hidden input untuk id_jabatan jika diperlukan -->
                                @if ($isCurrentDirector)
                                    <input type="hidden" name="id_jabatan" value="{{ $karyawan->id_jabatan }}">
                                @endif
                            </div>
                            <div class="mb-4">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required
                                    value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}">
                                @error('tempat_lahir')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                    value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}">
                                @error('tanggal_lahir')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" required
                                    value="{{ old('alamat', $karyawan->alamat) }}">
                                @error('alamat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="no_telp" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" required
                                    value="{{ old('no_telp', $karyawan->no_telp) }}">
                                @error('no_telp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto">
                                @error('foto')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <img id="foto-preview"
                                    src="{{ $karyawan->foto ? asset('storage/' . $karyawan->foto) : '' }}"
                                    alt="Preview Foto"
                                    style="display: {{ $karyawan->foto ? 'block' : 'none' }}; margin-top: 10px;"
                                    width="150" height="200">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                            <script>
                                document.getElementById('foto').addEventListener('change', function(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var imgElement = document.getElementById('foto-preview');
                                        imgElement.src = reader.result;
                                        imgElement.style.display = 'block';
                                    }
                                    if (event.target.files[0]) {
                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                });
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

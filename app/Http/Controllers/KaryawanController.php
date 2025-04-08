<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{

    public function index()
    {
        $karyawan = DB::table('karyawan')
            ->join('jabatan', 'jabatan.id', '=', 'karyawan.id_jabatan')
            ->whereNull('karyawan.deleted_at') // Hanya mengambil karyawan yang belum dihapus
            ->select('karyawan.*', 'jabatan.nama as nama_jabatan')
            ->get();

        $jabatan = DB::table('jabatan')->get();

        return view("admin.karyawan", ["karyawan" => $karyawan, "jabatan" => $jabatan]);
    }

    public function create()
    {
        // Ambil semua jabatan
        $jabatan = DB::select('select * from jabatan');

        // Periksa apakah ada Director
        $hasDirector = DB::table('karyawan')
            ->join('jabatan', 'karyawan.id_jabatan', '=', 'jabatan.id')
            ->where('jabatan.nama', 'Director')
            ->exists();

        // Jika sudah ada Director, filter jabatan untuk tidak menampilkan Director
        if ($hasDirector) {
            $jabatan = DB::table('jabatan')
                ->where('nama', '!=', 'Director')
                ->get();
        }

        return view('admin.createKaryawan', [
            'jabatan' => $jabatan,
            'hasDirector' => $hasDirector
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:karyawan,id',
            'id_jabatan' => 'required',
            'nama' => 'required',
            'email' => 'required|unique:karyawan,email',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|file|image',
            'agama' => 'required',
            'no_telp' => 'required|unique:karyawan,no_telp',
        ]);

        $nama_file = null;
        if ($request->hasFile('foto')) {
            $ext = $request->foto->getClientOriginalExtension();
            $nama_file = "foto-" . time() . "." . $ext;
            $path = $request->foto->storeAs('public', $nama_file);
        }

        $karyawan = new Karyawan([
            'id' => $request->id,
            'id_jabatan' => $request->id_jabatan,
            'nama' => $request->nama,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'foto' => $nama_file,
            'agama' => $request->agama,
            'no_telp' => $request->no_telp,
        ]);

        $karyawan->save();
        return redirect()->route('karyawan.index')->with('success', 'Biodata "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $karyawan = Karyawan::find($id);
        $jabatanOptions = Jabatan::pluck('nama', 'id');

        // Cek apakah jabatan saat ini adalah Director
        $isCurrentDirector = $karyawan->jabatan->nama === 'Director';

        // Cek apakah ada karyawan lain yang sudah menjadi Director
        $isDirectorExists = Karyawan::whereHas('jabatan', function ($query) {
            $query->where('nama', 'Director');
        })->where('id', '!=', $karyawan->id)->exists();

        // Hapus opsi Director dari dropdown jika ada karyawan lain yang sudah menjadi Director
        if ($isDirectorExists) {
            $jabatanOptions = $jabatanOptions->filter(function ($namaJabatan, $kodeJabatan) {
                return $namaJabatan !== 'Director';
            });
        }

        return view('admin.editKaryawan', compact('karyawan', 'jabatanOptions', 'isCurrentDirector'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        // Validasi input
        $rules = [
            'id' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|file|image',
            'agama' => 'required',
            'no_telp' => 'required',
        ];

        // Tambahkan aturan validasi id_jabatan hanya jika bukan director
        if ($karyawan->id_jabatan != 'DIRECTOR') {
            $rules['id_jabatan'] = 'required';
        }

        $request->validate($rules);

        // Menangani upload foto
        $nama_file = $karyawan->foto;
        if ($request->hasFile('foto')) {
            $ext = $request->foto->getClientOriginalExtension();
            $nama_file = "foto-" . time() . "." . $ext;
            $path = $request->foto->storeAs('public', $nama_file);

            if (Storage::exists('public/' . $karyawan->foto)) {
                Storage::delete('public/' . $karyawan->foto);
            }
        } else {
            $nama_file = $karyawan->foto;
        }

        // Update session foto hanya jika pengguna sedang mengubah foto mereka sendiri
        if ($karyawan->id == session('id_karyawan')) {
            session(['foto' => $nama_file]);
        }

        // Update data karyawan
        $karyawan->update([
            'id' => $request->id,
            'id_jabatan' => $request->id_jabatan,
            'nama' => $request->nama,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'foto' => $nama_file,
            'agama' => $request->agama,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Biodata "' . $request->nama . '" berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $karyawan = DB::table('karyawan')
            ->join('jabatan', 'karyawan.id_jabatan', '=', 'jabatan.id')
            ->select('karyawan.*', 'jabatan.nama as nama_jabatan')
            ->where('karyawan.id', $id)
            ->first();

        return view('Admin.showKaryawan', ['karyawan' => $karyawan]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Karyawan $karyawan)
    {
        $loggedInIdKaryawan = session('id_karyawan');

        if ($karyawan->id == $loggedInIdKaryawan) {
            Karyawan::find($karyawan->id)->delete();

            session()->flush();
            return redirect()->route('login')->with('success', 'Biodata karyawan Anda telah dihapus. Silakan gunakan akun lain.');
        }

        Karyawan::find($karyawan->id)->delete();

        return redirect('karyawan')->with('success', 'Biodata "' . $karyawan->nama . '" berhasil dinon-aktifkan.');
    }

}

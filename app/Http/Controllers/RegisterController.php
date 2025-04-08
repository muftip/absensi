<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('users')
            ->join('karyawan', 'karyawan.id', '=', 'users.id_karyawan')
            ->whereNull('karyawan.deleted_at')
            ->select('users.*', 'karyawan.nama')
            ->get();

        $karyawan = DB::table('karyawan')
            ->whereNull('deleted_at')
            ->get();

        $assignedUserIds = $users->pluck('id_karyawan')->toArray();
        $availableKaryawan = $karyawan->filter(fn($k) => !in_array($k->id, $assignedUserIds));

        return view("user.index", [
            "users" => $users,
            "karyawan" => $karyawan,
            "allKaryawanHaveAccess" => $availableKaryawan->isEmpty(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_karyawan' => 'required|unique:users,id_karyawan',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'hak_akses' => 'required|in:Admin,Director,General Manager'
        ]);

        User::create([
            'id_karyawan' => $data['id_karyawan'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'hak_akses' => $data['hak_akses']
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allUsers = DB::table('karyawan')->whereNull('deleted_at')->get();
        $assignedUserIds = DB::table('users')->pluck('id_karyawan')->toArray();
        $availableUsers = $allUsers->filter(fn($user) => !in_array($user->id, $assignedUserIds));
        $hasDirectorAccess = DB::table('users')->where('hak_akses', 'Director')->exists();

        return view('user.register', [
            'users' => $availableUsers,
            'hasDirectorAccess' => $hasDirectorAccess
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view("user.edit", ["user" => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'hak_akses' => 'required|in:Admin,Director,General Manager'
        ]);

        $user->username = $data['username'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->hak_akses = $data['hak_akses'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_karyawan)
    {
        $loggedInIdKaryawan = session('id_karyawan');
        $user = User::where('id_karyawan', $id_karyawan)->firstOrFail();

        if ($user->id_karyawan == $loggedInIdKaryawan) {
            $user->delete();
            session()->flush();
            return redirect()->route('login')->with('success', 'Akun Anda telah dihapus.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
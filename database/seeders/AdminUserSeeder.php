<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id_karyawan' => 1, // pastikan id ini ada di tabel karyawan
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'hak_akses' => 'Admin',
        ]);
    }
}

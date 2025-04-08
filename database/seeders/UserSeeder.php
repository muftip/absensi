<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_karyawan' => "001",
                'username' => "123",
                'hak_akses' => "Director",
                'password' => Hash::make(123456)
            ],
            [
                'id_karyawan' => "002",
                'username' => "1234",
                'hak_akses' => "General Manager",
                'password' => Hash::make(123456)
            ],
            [
                'id_karyawan' => "003",
                'username' => "12345",
                'hak_akses' => "Admin",
                'password' => Hash::make(123456)
            ],
        ]);
    }
}

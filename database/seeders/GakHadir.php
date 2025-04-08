<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GakHadir extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawan_izin')->insert([
            [
                'id_karyawan' => "001",
                'id_absensi' => "2",
                'izin' => 0,
                'keterangan' => "",
            ],
            [
                'id_karyawan' => "002",
                'id_absensi' => "2",
                'izin' => 1,
                'keterangan' => "Sakit",
            ],
            [
                'id_karyawan' => "004",
                'id_absensi' => "2",
                'izin' => 1,
                'keterangan' => "Sakit",
            ],
            [
                'id_karyawan' => "001",
                'id_absensi' => "3",
                'izin' => 0,
                'keterangan' => "",
            ],
            [
                'id_karyawan' => "003",
                'id_absensi' => "3",
                'izin' => 0,
                'keterangan' => "",
            ],
            [
                'id_karyawan' => "005",
                'id_absensi' => "3",
                'izin' => 1,
                'keterangan' => "Sakit",
            ],
            [
                'id_karyawan' => "001",
                'id_absensi' => "4",
                'izin' => 1,
                'keterangan' => "Sakit",
            ],
            [
                'id_karyawan' => "002",
                'id_absensi' => "4",
                'izin' => 0,
                'keterangan' => "",
            ],
        ]);
    }
}

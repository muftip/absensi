<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Hadir extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawan_absensi')->insert([
            [
                'id_karyawan' => "001",
                'id_absensi' => "1",
                'waktu_masuk' => "07:50:00",
                'waktu_keluar' => "16:45:00"
            ],
            [
                'id_karyawan' => "002",
                'id_absensi' => "1",
                'waktu_masuk' => "07:30:00",
                'waktu_keluar' => "16:40:30"
            ],
            [
                'id_karyawan' => "003",
                'id_absensi' => "1",
                'waktu_masuk' => "09:10:20",
                'waktu_keluar' => "16:55:40"
            ],
            [
                'id_karyawan' => "004",
                'id_absensi' => "1",
                'waktu_masuk' => "10:20:45",
                'waktu_keluar' => "18:30:30"
            ],
            [
                'id_karyawan' => "005",
                'id_absensi' => "1",
                'waktu_masuk' => "07:30:45",
                'waktu_keluar' => "17:10:50"
            ],
            [
                'id_karyawan' => "003",
                'id_absensi' => "2",
                'waktu_masuk' => "07:36:40",
                'waktu_keluar' => "16:56:00"
            ],
            [
                'id_karyawan' => "005",
                'id_absensi' => "2",
                'waktu_masuk' => "08:30:00",
                'waktu_keluar' => "18:10:00"
            ],
            [
                'id_karyawan' => "002",
                'id_absensi' => "3",
                'waktu_masuk' => "11:20:30",
                'waktu_keluar' => "19:35:20"
            ],
            [
                'id_karyawan' => "004",
                'id_absensi' => "3",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "003",
                'id_absensi' => "4",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "004",
                'id_absensi' => "4",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "005",
                'id_absensi' => "4",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "001",
                'id_absensi' => "5",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "002",
                'id_absensi' => "5",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "003",
                'id_absensi' => "5",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "004",
                'id_absensi' => "5",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
            [
                'id_karyawan' => "005",
                'id_absensi' => "5",
                'waktu_masuk' => "10:00:00",
                'waktu_keluar' => "17:00:00"
            ],
        ]);
    }
}

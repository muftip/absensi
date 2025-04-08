<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class HariLiburSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('libur')->insert([
            [
                'keterangan' => "Hari Buruh Internasional",
                'tanggal_mulai' => "2024-05-01",
                'tanggal_selesai' => "2024-05-01",
            ],
            [
                'keterangan' => "Hari Raya Idul Adha 1445 Hijriah",
                'tanggal_mulai' => "2024-06-17",
                'tanggal_selesai' => "2024-06-18",
            ],
            [
                'keterangan' => "Hari Kemerdekaan Indonesia",
                'tanggal_mulai' => "2024-08-17",
                'tanggal_selesai' => "2024-08-17",
            ],
        ]);
    }
}

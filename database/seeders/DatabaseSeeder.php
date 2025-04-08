<?php

namespace Database\Seeders;

use Database\Seeders\HariLiburSeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\KaryawanSeeder;
use Database\Seeders\Absensi;
use Database\Seeders\Hadir;
use Database\Seeders\GakHadir;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(HariLiburSeeder::class);
        $this->call(JabatanSeeder::class);
        $this->call(KaryawanSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(Absensi::class);
        $this->call(Hadir::class);
        $this->call(GakHadir::class);
    }
}

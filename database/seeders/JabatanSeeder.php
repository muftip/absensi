<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jabatan')->insert([
            [
                'id' => "AA",
                'nama' => "Director",
            ],
            [
                'id' => "AB",
                'nama' => "General Manager",
            ],
            [
                'id' => "AC",
                'nama' => "Staff Administration",
            ],
            [
                'id' => "AD",
                'nama' => "Accounting and Finance",
            ],
            [
                'id' => "AE",
                'nama' => "Sales Manager",
            ],
            [
                'id' => "AF",
                'nama' => "Warehouse Manager",
            ],
            [
                'id' => "AG",
                'nama' => "Sales Supervisor",
            ],
            [
                'id' => "AH",
                'nama' => "Salesman",
            ],
            [
                'id' => "AI",
                'nama' => "Driver",
            ],
            [
                'id' => "AJ",
                'nama' => "Helper",
            ],

        ]);
    }
}

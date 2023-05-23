<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'id' => 1,
                'name' => 'Avis',
                'display_order' => 10,
                'active' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Budget',
                'display_order' => 20,
                'active' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Payless',
                'display_order' => 30,
                'active' => 1,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insert([
            [
                'code' => 'NAM',
                'name' => 'North America & Canada',
                'display_order' => 1,
            ],
            [
                'code' => 'EMEA',
                'name' => 'Europe, Middle East and Africa',
                'display_order' => 2,
            ],
            [
                'code' => 'LAC',
                'name' => 'Latin America & the Caribbean',
                'display_order' => 3,
            ],
            [
                'code' => 'APAC',
                'name' => 'Asia Pacific',
                'display_order' => 4,
            ],
        ]);
    }
}

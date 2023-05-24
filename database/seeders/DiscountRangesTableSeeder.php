<?php

namespace Database\Seeders;

use App\Models\DiscountRange;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountRangesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscountRange::factory(20)->create();
        fake()->unique(true);
        DiscountRange::factory(20)->create();
        fake()->unique(true);
        DiscountRange::factory(20)->create();
    }
}

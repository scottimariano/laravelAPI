<?php

namespace Database\Seeders;

use App\Models\AccessType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('access_types')->insert([
            [
                'code' => 'A',
                'name' => 'Cliente Final',
                'display_order' => 1,
            ],
            [
                'code' => 'B',
                'name' => 'Agencia',
                'display_order' => 2,
            ],
            [
                'code' => 'C',
                'name' => 'Corporativo',
                'display_order' => 3,
            ],
        ]);
    }
}

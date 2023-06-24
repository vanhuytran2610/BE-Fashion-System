<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::insert([
            ['color' => 'Red'],
            ['color' => 'Blue'],
            ['color' => 'Green'],
            ['color' => 'Yellow'],
            ['color' => 'Orange'],
            ['color' => 'White'],
            ['color' => 'Black'],
            ['color' => 'Brown'],
            ['color' => 'Purple'],
        ]);
    }
}

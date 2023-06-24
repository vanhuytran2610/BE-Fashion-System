<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Size::insert([
            ['size' => 'XS', 'weight' => '40kg - 49kg', 'height' => '150cm - 155cm'],
            ['size' => 'S', 'weight' => '49kg - 55kg', 'height' => '155cm - 160cm'],
            ['size' => 'M', 'weight' => '55kg - 65kg', 'height' => '165cm - 169cm'],
            ['size' => 'L', 'weight' => '66kg - 70kg', 'height' => '170cm - 174cm'],
            ['size' => 'XL', 'weight' => '70kg - 80kg', 'height' => '175cm - 180cm'],
            ['size' => 'XXL', 'weight' => '80kg - 100kg', 'height' => '181cm - 190cm'],
        ]);
    }
}

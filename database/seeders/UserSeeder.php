<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'firstname' => 'Huy',
            'lastname' => 'Admin',
            'email' => 'vanhuytran2610@gmail.com',
            'password' => Hash::make('admin1234'),
            'role_id' => 1
        ]);
    }
}

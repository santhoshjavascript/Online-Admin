<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {


        User::create([
            'name' => 'Zinna',
            'email' => 'zinna@gmail.com',
            'password' => bcrypt('zinna@123'),
            'role' => 'admin',
        ]);

    }
}
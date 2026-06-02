<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin MagangKu',
            'email' => 'admin@magangku.com',
            'password_hash' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '08123456789',
            'avatar_url' => null,
        ]);

        User::create([
            'name' => 'Karim Benzema',
            'email' => 'karimbenzema@gmail.com',
            'password_hash' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '081111111111',
            'avatar_url' => null,
        ]);
    }
}
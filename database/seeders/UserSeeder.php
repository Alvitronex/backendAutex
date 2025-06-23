<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        user::create([
            'username' => 'admin',
            'name' => 'Mario',
            'address' => '123 Main St',
            'phone' => '1234567890',
            'DUI' => '01234567-8',
            'email' => 'mario@mail.com',
            'password' => Hash::make('123456'), // Asegúrate de usar un hash seguro
            'theme' => 'light',
        ]);

        User::create([
            'username' => 'user',
            'name' => 'Juan',
            'address' => '456 Elm St',
            'phone' => '0987654321',
            'DUI' => '87654321-0',
            'email' => 'ra23333@uls.edu.sv',
            'password' => Hash::make('123456'), // Asegúrate de usar un hash seguro
            'theme' => 'dark',
        ]);
    }
}

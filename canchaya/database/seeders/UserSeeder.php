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
            'name'     => 'Administrador',
            'email'    => 'admin@canchaya.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Carlos López',
            'email'    => 'carlos@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);

        User::create([
            'name'     => 'María García',
            'email'    => 'maria@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);

        User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'juan@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);
    }
}
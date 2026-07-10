<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@canchaya.com',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@gmail.com',
                'password' => 'password',
                'role' => 'client',
            ],
            [
                'name' => 'María García',
                'email' => 'maria@gmail.com',
                'password' => 'password',
                'role' => 'client',
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@gmail.com',
                'password' => 'password',
                'role' => 'client',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);

            $user->forceFill([
                'name'              => $data['name'],
                'password'          => Hash::make($data['password']),
                'role'              => $data['role'],
                'email_verified_at' => now(),
            ])->save();
        }
    }
}
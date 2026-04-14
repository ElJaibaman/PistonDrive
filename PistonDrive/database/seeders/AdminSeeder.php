<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@taller.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Admin2025!'),
                'rol'      => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'secretaria@taller.com'],
            [
                'name'     => 'Laura Espinoza',
                'password' => Hash::make('Secretaria2025!'),
                'rol'      => 'secretaria',
            ]
        );

        User::firstOrCreate(
            ['email' => 'encargado@taller.com'],
            [
                'name'     => 'Carlos Funez',
                'password' => Hash::make('Encargado2025!'),
                'rol'      => 'encargado',
            ]
        );
    }
}
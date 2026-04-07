<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Crea el usuario administrador inicial del sistema.
     * Corre con: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@taller.com'],
            [
                'name'     => 'Administrador',
                'email'    => 'admin@taller.com',
                'password' => Hash::make('Admin2025!'),
                'rol'      => 'admin',
            ]
        );

        $this->command->info('Admin creado: admin@taller.com / Admin2025!');
        $this->command->warn('Cambia la contraseña después del primer login.');
    }
}
<?php

namespace Database\Seeders;

use App\Models\Mecanico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MecanicoSeeder extends Seeder
{
    public function run(): void
    {
        $mecanicos = [
            ['nombre' => 'José Martínez',    'telefono' => '9876-1234', 'activo' => true,  'email' => 'jose.martinez@taller.com',  'password' => 'Mecanico2025!'],
            ['nombre' => 'Diego Alvarado',   'telefono' => '9654-5678', 'activo' => true,  'email' => 'diego.alvarado@taller.com', 'password' => 'Mecanico2025!'],
            ['nombre' => 'Kevin Moncada',    'telefono' => '9321-9012', 'activo' => true,  'email' => 'kevin.moncada@taller.com',  'password' => 'Mecanico2025!'],
            ['nombre' => 'Josué Perdomo',    'telefono' => '9789-3456', 'activo' => true,  'email' => 'josue.perdomo@taller.com',  'password' => 'Mecanico2025!'],
            ['nombre' => 'Nain Cáceres',     'telefono' => '9456-7890', 'activo' => true,  'email' => 'nain.caceres@taller.com',   'password' => 'Mecanico2025!'],
            ['nombre' => 'Bryan Reconco',    'telefono' => '9123-4567', 'activo' => true,  'email' => 'bryan.reconco@taller.com',  'password' => 'Mecanico2025!'],
            ['nombre' => 'Elvin Suazo',      'telefono' => '9987-6543', 'activo' => true,  'email' => 'elvin.suazo@taller.com',    'password' => 'Mecanico2025!'],
            ['nombre' => 'Ronald Flores',    'telefono' => '9654-3210', 'activo' => false, 'email' => 'ronald.flores@taller.com',  'password' => 'Mecanico2025!'],
            ['nombre' => 'Omar Turcios',     'telefono' => '9321-0987', 'activo' => true,  'email' => 'omar.turcios@taller.com',   'password' => 'Mecanico2025!'],
            ['nombre' => 'Wilmer Aguilera',  'telefono' => '9888-2345', 'activo' => false, 'email' => 'wilmer.aguilera@taller.com','password' => 'Mecanico2025!'],
        ];

        foreach ($mecanicos as $datos) {
            $mecanico = Mecanico::firstOrCreate(
                ['nombre' => $datos['nombre']],
                [
                    'telefono' => $datos['telefono'],
                    'activo'   => $datos['activo'],
                ]
            );

            $user = User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'name'        => $datos['nombre'],
                    'password'    => Hash::make($datos['password']),
                    'rol'         => 'mecanico',
                    'mecanico_id' => $mecanico->id,
                ]
            );
        }
    }
}
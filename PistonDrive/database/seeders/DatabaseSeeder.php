<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            MecanicoSeeder::class,
            ClienteSeeder::class,
            VehiculoSeeder::class,
            OrdenTrabajoSeeder::class,
            CotizacionSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
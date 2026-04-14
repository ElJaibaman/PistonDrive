<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            ['nombre' => 'Diego Espinoza',     'telefono' => '9876-5432', 'correo' => 'diego.espinoza@gmail.com',    'numero_identidad' => '0801-1990-12345', 'procedencia' => 'Col. Kennedy, Tegucigalpa'],
            ['nombre' => 'Alejandra Romero',   'telefono' => '9654-3210', 'correo' => 'ale.romero@gmail.com',        'numero_identidad' => '0801-1988-23456', 'procedencia' => 'Res. La Alameda, Tegucigalpa'],
            ['nombre' => 'Ledin Alvarado',     'telefono' => '9321-0987', 'correo' => 'ledin.alvarado@yahoo.com',   'numero_identidad' => '0501-1995-34567', 'procedencia' => 'Col. Loarque, San Pedro Sula'],
            ['nombre' => 'Jonatan Moncada',    'telefono' => '9789-6543', 'correo' => 'jmoncada@hotmail.com',       'numero_identidad' => '0801-1992-45678', 'procedencia' => 'Barrio Abajo, Comayagua'],
            ['nombre' => 'Nain Cáceres',       'telefono' => '9456-3210', 'correo' => 'naincaceres@gmail.com',      'numero_identidad' => '0101-1987-56789', 'procedencia' => 'Col. Trejo, La Ceiba'],
            ['nombre' => 'Karen López',        'telefono' => '9123-6789', 'correo' => 'karen.lopez@gmail.com',      'numero_identidad' => '0801-1993-67890', 'procedencia' => 'Col. Palmira, Tegucigalpa'],
            ['nombre' => 'Mario Reconco',      'telefono' => '9987-1234', 'correo' => 'mario.reconco@gmail.com',    'numero_identidad' => '0501-1985-78901', 'procedencia' => 'Res. Los Álamos, San Pedro Sula'],
            ['nombre' => 'Sofía Turcios',      'telefono' => '9654-9876', 'correo' => 'sofia.turcios@outlook.com',  'numero_identidad' => '0801-1991-89012', 'procedencia' => 'Col. Lomas del Guijarro, Tegucigalpa'],
            ['nombre' => 'Erick Aguilera',     'telefono' => '9321-5678', 'correo' => 'erick.aguilera@gmail.com',   'numero_identidad' => '0301-1989-90123', 'procedencia' => 'Barrio El Centro, Choluteca'],
            ['nombre' => 'Paola Suazo',        'telefono' => '9888-4321', 'correo' => 'psuazo@gmail.com',           'numero_identidad' => '0801-1994-01234', 'procedencia' => 'Col. América, Tegucigalpa'],
            ['nombre' => 'Roberto Perdomo',    'telefono' => '9456-8765', 'correo' => 'rperdomo@yahoo.com',         'numero_identidad' => '0501-1986-12345', 'procedencia' => 'Res. Villanueva, Cortés'],
            ['nombre' => 'Daniela Flores',     'telefono' => '9123-2109', 'correo' => 'dflores@gmail.com',          'numero_identidad' => '0801-1996-23456', 'procedencia' => 'Col. Residencial Montebello, Tegucigalpa'],
            ['nombre' => 'Héctor Martínez',    'telefono' => '9987-5432', 'correo' => 'hmartinez@gmail.com',        'numero_identidad' => '0801-1983-34567', 'procedencia' => 'Barrio La Granja, Tegucigalpa'],
            ['nombre' => 'Valeria Pineda',     'telefono' => '9654-2109', 'correo' => 'vpineda@hotmail.com',        'numero_identidad' => '0101-1997-45678', 'procedencia' => 'Col. Toronjal, La Ceiba'],
            ['nombre' => 'Óscar Mejía',        'telefono' => '9321-8765', 'correo' => 'omejia@gmail.com',           'numero_identidad' => '0501-1984-56789', 'procedencia' => 'Barrio Río Piedras, San Pedro Sula'],
            ['nombre' => 'Fernanda Zelaya',    'telefono' => '9789-1234', 'correo' => 'fzelaya@gmail.com',          'numero_identidad' => '0801-1998-67890', 'procedencia' => 'Col. La Esperanza, Tegucigalpa'],
            ['nombre' => 'Andrés Orellana',    'telefono' => '9456-6789', 'correo' => 'aorellana@gmail.com',        'numero_identidad' => '0801-1990-78901', 'procedencia' => 'Res. Las Colinas, Tegucigalpa'],
            ['nombre' => 'Melissa Corrales',   'telefono' => '9123-3456', 'correo' => 'mcorrales@outlook.com',      'numero_identidad' => '0301-1995-89012', 'procedencia' => 'Col. El Pedregal, Choluteca'],
            ['nombre' => 'Luis Triminio',      'telefono' => '9987-7890', 'correo' => 'ltriminio@gmail.com',        'numero_identidad' => '0501-1988-90123', 'procedencia' => 'Aldea El Progreso, Yoro'],
            ['nombre' => 'Ana Elvir',          'telefono' => '9654-4567', 'correo' => 'aelvir@gmail.com',           'numero_identidad' => '0801-1992-01234', 'procedencia' => 'Col. Tres Caminos, Tegucigalpa'],
        ];

        foreach ($clientes as $datos) {
            Cliente::firstOrCreate(
                ['numero_identidad' => $datos['numero_identidad']],
                $datos
            );
        }
    }
}
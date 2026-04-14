<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $vehiculos = [
            ['cliente' => 'Diego Espinoza',     'marca' => 'Toyota',      'modelo' => 'Corolla',      'anio' => 2018, 'placa' => 'AEI-1234', 'color' => 'Blanco',   'observaciones' => 'Vehículo en buen estado general'],
            ['cliente' => 'Diego Espinoza',     'marca' => 'Honda',       'modelo' => 'CR-V',         'anio' => 2020, 'placa' => 'AFB-5678', 'color' => 'Negro',    'observaciones' => null],
            ['cliente' => 'Alejandra Romero',   'marca' => 'Nissan',      'modelo' => 'Sentra',       'anio' => 2016, 'placa' => 'AGC-2345', 'color' => 'Plateado', 'observaciones' => 'Golpe leve en parachoques trasero'],
            ['cliente' => 'Ledin Alvarado',     'marca' => 'Hyundai',     'modelo' => 'Tucson',       'anio' => 2019, 'placa' => 'AHD-6789', 'color' => 'Gris',     'observaciones' => null],
            ['cliente' => 'Jonatan Moncada',    'marca' => 'Kia',         'modelo' => 'Sportage',     'anio' => 2021, 'placa' => 'AIE-3456', 'color' => 'Rojo',     'observaciones' => null],
            ['cliente' => 'Nain Cáceres',       'marca' => 'Chevrolet',   'modelo' => 'Spark',        'anio' => 2017, 'placa' => 'AJF-7890', 'color' => 'Azul',     'observaciones' => 'Rayón en puerta delantera derecha'],
            ['cliente' => 'Karen López',        'marca' => 'Toyota',      'modelo' => 'RAV4',         'anio' => 2022, 'placa' => 'AKG-4567', 'color' => 'Blanco',   'observaciones' => null],
            ['cliente' => 'Mario Reconco',      'marca' => 'Ford',        'modelo' => 'F-150',        'anio' => 2015, 'placa' => 'ALH-8901', 'color' => 'Negro',    'observaciones' => 'Camioneta con caja de carga'],
            ['cliente' => 'Sofía Turcios',      'marca' => 'Mazda',       'modelo' => 'CX-5',         'anio' => 2020, 'placa' => 'AMI-5678', 'color' => 'Rojo',     'observaciones' => null],
            ['cliente' => 'Erick Aguilera',     'marca' => 'Mitsubishi',  'modelo' => 'Montero',      'anio' => 2014, 'placa' => 'ANJ-2345', 'color' => 'Plateado', 'observaciones' => 'Vehículo con alto kilometraje'],
            ['cliente' => 'Paola Suazo',        'marca' => 'Honda',       'modelo' => 'Civic',        'anio' => 2019, 'placa' => 'AOK-6789', 'color' => 'Gris',     'observaciones' => null],
            ['cliente' => 'Roberto Perdomo',    'marca' => 'Toyota',      'modelo' => 'Hilux',        'anio' => 2021, 'placa' => 'APL-3456', 'color' => 'Blanco',   'observaciones' => 'Doble cabina 4x4'],
            ['cliente' => 'Daniela Flores',     'marca' => 'Volkswagen',  'modelo' => 'Golf',         'anio' => 2018, 'placa' => 'AQM-7890', 'color' => 'Azul',     'observaciones' => null],
            ['cliente' => 'Héctor Martínez',    'marca' => 'Nissan',      'modelo' => 'Frontier',     'anio' => 2016, 'placa' => 'ARN-4567', 'color' => 'Negro',    'observaciones' => 'Camioneta de trabajo'],
            ['cliente' => 'Valeria Pineda',     'marca' => 'Hyundai',     'modelo' => 'Elantra',      'anio' => 2020, 'placa' => 'ASO-8901', 'color' => 'Blanco',   'observaciones' => null],
            ['cliente' => 'Óscar Mejía',        'marca' => 'Kia',         'modelo' => 'Seltos',       'anio' => 2022, 'placa' => 'ATP-5678', 'color' => 'Rojo',     'observaciones' => null],
            ['cliente' => 'Fernanda Zelaya',    'marca' => 'Suzuki',      'modelo' => 'Vitara',       'anio' => 2019, 'placa' => 'AUQ-2345', 'color' => 'Naranja',  'observaciones' => 'Vehículo recién adquirido'],
            ['cliente' => 'Andrés Orellana',    'marca' => 'Toyota',      'modelo' => 'Land Cruiser', 'anio' => 2017, 'placa' => 'AVR-6789', 'color' => 'Gris',     'observaciones' => '4x4, uso en carretera'],
            ['cliente' => 'Melissa Corrales',   'marca' => 'Honda',       'modelo' => 'Fit',          'anio' => 2015, 'placa' => 'AWS-3456', 'color' => 'Verde',    'observaciones' => null],
            ['cliente' => 'Luis Triminio',      'marca' => 'Chevrolet',   'modelo' => 'Silverado',    'anio' => 2018, 'placa' => 'AXT-7890', 'color' => 'Negro',    'observaciones' => 'Camioneta de trabajo pesado'],
            ['cliente' => 'Ana Elvir',          'marca' => 'Ford',        'modelo' => 'Escape',       'anio' => 2021, 'placa' => 'AYU-4567', 'color' => 'Azul',     'observaciones' => null],
            ['cliente' => 'Karen López',        'marca' => 'Mazda',       'modelo' => 'Mazda3',       'anio' => 2020, 'placa' => 'AZV-8901', 'color' => 'Plateado', 'observaciones' => null],
            ['cliente' => 'Ledin Alvarado',     'marca' => 'Mitsubishi',  'modelo' => 'L200',         'anio' => 2016, 'placa' => 'BAW-5678', 'color' => 'Blanco',   'observaciones' => 'Pickup doble cabina'],
            ['cliente' => 'Nain Cáceres',       'marca' => 'Toyota',      'modelo' => 'Fortuner',     'anio' => 2022, 'placa' => 'BBX-2345', 'color' => 'Negro',    'observaciones' => null],
            ['cliente' => 'Jonatan Moncada',    'marca' => 'Nissan',      'modelo' => 'X-Trail',      'anio' => 2019, 'placa' => 'BCY-6789', 'color' => 'Gris',     'observaciones' => null],
        ];

        foreach ($vehiculos as $datos) {
            $cliente = Cliente::where('nombre', $datos['cliente'])->first();
            if (!$cliente) continue;

            Vehiculo::firstOrCreate(
                ['placa' => $datos['placa']],
                [
                    'cliente_id'    => $cliente->id,
                    'marca'         => $datos['marca'],
                    'modelo'        => $datos['modelo'],
                    'anio'          => $datos['anio'],
                    'color'         => $datos['color'],
                    'observaciones' => $datos['observaciones'],
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\DetalleServicio;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Seeder;

class DetalleServicioSeeder extends Seeder
{
    public function run(): void
    {
        $detalles = [
            ['placa' => 'AEI-1234', 'fecha' => '2026-01-05', 'servicios' => [
                ['Inspección visual de frenos',       150.00],
                ['Prueba de carretera post-reparación', 0.00],
            ]],
            ['placa' => 'AFB-5678', 'fecha' => '2026-01-10', 'servicios' => [
                ['Revisión de nivel de fluidos',      100.00],
                ['Limpieza de inyectores',            350.00],
            ]],
            ['placa' => 'AGC-2345', 'fecha' => '2026-01-15', 'servicios' => [
                ['Escaneo OBD2',                      200.00],
                ['Limpieza de cuerpo de aceleración', 250.00],
            ]],
            ['placa' => 'ALH-8901', 'fecha' => '2026-02-10', 'servicios' => [
                ['Revisión de tensión de correa',     150.00],
                ['Ajuste de válvulas',                500.00],
            ]],
            ['placa' => 'ANJ-2345', 'fecha' => '2026-02-20', 'servicios' => [
                ['Diagnóstico eléctrico completo',    400.00],
                ['Revisión de fusibles y relés',      100.00],
            ]],
        ];

        foreach ($detalles as $datos) {
            $orden = OrdenTrabajo::whereHas('vehiculo', fn($q) => $q->where('placa', $datos['placa']))
                ->where('fecha_ingreso', $datos['fecha'])
                ->first();

            if (!$orden) continue;

            foreach ($datos['servicios'] as [$desc, $costo]) {
                DetalleServicio::firstOrCreate(
                    ['orden_trabajo_id' => $orden->id, 'descripcion' => $desc],
                    ['costo' => $costo]
                );
            }
        }
    }
}
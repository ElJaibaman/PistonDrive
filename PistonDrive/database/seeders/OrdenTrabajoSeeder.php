<?php

namespace Database\Seeders;

use App\Models\Mecanico;
use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class OrdenTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $vehiculos  = Vehiculo::with('cliente')->get();
        $mecanicos  = Mecanico::where('activo', true)->get();

        $ordenes = [
            // Órdenes ENTREGADAS (flujo completo)
            ['placa' => 'AEI-1234', 'mecanico' => 'José Martínez',   'fecha_ingreso' => '2026-01-05', 'fecha_prometida' => '2026-01-07', 'fecha_entrega' => '2026-01-08', 'estado' => 'entregado', 'km' => 45000, 'obs' => 'Cliente reporta ruido en frenos delanteros'],
            ['placa' => 'AFB-5678', 'mecanico' => 'Diego Alvarado',  'fecha_ingreso' => '2026-01-10', 'fecha_prometida' => '2026-01-12', 'fecha_entrega' => '2026-01-13', 'estado' => 'entregado', 'km' => 32000, 'obs' => 'Cambio de aceite y filtros'],
            ['placa' => 'AGC-2345', 'mecanico' => 'Kevin Moncada',   'fecha_ingreso' => '2026-01-15', 'fecha_prometida' => '2026-01-17', 'fecha_entrega' => '2026-01-18', 'estado' => 'entregado', 'km' => 78000, 'obs' => 'Revisión general del motor'],
            ['placa' => 'AHD-6789', 'mecanico' => 'Josué Perdomo',   'fecha_ingreso' => '2026-01-20', 'fecha_prometida' => '2026-01-22', 'fecha_entrega' => '2026-01-23', 'estado' => 'entregado', 'km' => 55000, 'obs' => 'Falla en sistema de enfriamiento'],
            ['placa' => 'AIE-3456', 'mecanico' => 'Nain Cáceres',    'fecha_ingreso' => '2026-01-25', 'fecha_prometida' => '2026-01-27', 'fecha_entrega' => '2026-01-28', 'estado' => 'entregado', 'km' => 28000, 'obs' => 'Cambio de pastillas y discos de freno'],
            ['placa' => 'AJF-7890', 'mecanico' => 'Bryan Reconco',   'fecha_ingreso' => '2026-02-01', 'fecha_prometida' => '2026-02-03', 'fecha_entrega' => '2026-02-04', 'estado' => 'entregado', 'km' => 92000, 'obs' => 'Revisión de suspensión delantera'],
            ['placa' => 'AKG-4567', 'mecanico' => 'Elvin Suazo',     'fecha_ingreso' => '2026-02-05', 'fecha_prometida' => '2026-02-07', 'fecha_entrega' => '2026-02-08', 'estado' => 'entregado', 'km' => 41000, 'obs' => 'Fuga de aceite en cárter'],
            ['placa' => 'ALH-8901', 'mecanico' => 'Omar Turcios',    'fecha_ingreso' => '2026-02-10', 'fecha_prometida' => '2026-02-12', 'fecha_entrega' => '2026-02-13', 'estado' => 'entregado', 'km' => 120000,'obs' => 'Cambio de correa de distribución'],
            ['placa' => 'AMI-5678', 'mecanico' => 'José Martínez',   'fecha_ingreso' => '2026-02-15', 'fecha_prometida' => '2026-02-17', 'fecha_entrega' => '2026-02-18', 'estado' => 'entregado', 'km' => 67000, 'obs' => 'Alineación y balanceo'],
            ['placa' => 'ANJ-2345', 'mecanico' => 'Diego Alvarado',  'fecha_ingreso' => '2026-02-20', 'fecha_prometida' => '2026-02-22', 'fecha_entrega' => '2026-02-23', 'estado' => 'entregado', 'km' => 85000, 'obs' => 'Sistema eléctrico, falla en alternador'],
            ['placa' => 'AOK-6789', 'mecanico' => 'Kevin Moncada',   'fecha_ingreso' => '2026-02-25', 'fecha_prometida' => '2026-02-27', 'fecha_entrega' => '2026-02-28', 'estado' => 'entregado', 'km' => 39000, 'obs' => 'Revisión de transmisión automática'],
            ['placa' => 'APL-3456', 'mecanico' => 'Josué Perdomo',   'fecha_ingreso' => '2026-03-01', 'fecha_prometida' => '2026-03-03', 'fecha_entrega' => '2026-03-04', 'estado' => 'entregado', 'km' => 58000, 'obs' => 'Cambio de batería y revisión eléctrica'],
            // Órdenes TERMINADAS (listas para ticket)
            ['placa' => 'AQM-7890', 'mecanico' => 'Nain Cáceres',    'fecha_ingreso' => '2026-03-05', 'fecha_prometida' => '2026-03-07', 'fecha_entrega' => null,         'estado' => 'terminado', 'km' => 72000, 'obs' => 'Falla en sistema de aire acondicionado'],
            ['placa' => 'ARN-4567', 'mecanico' => 'Bryan Reconco',   'fecha_ingreso' => '2026-03-08', 'fecha_prometida' => '2026-03-10', 'fecha_entrega' => null,         'estado' => 'terminado', 'km' => 48000, 'obs' => 'Cambio de amortiguadores traseros'],
            ['placa' => 'ASO-8901', 'mecanico' => 'Elvin Suazo',     'fecha_ingreso' => '2026-03-10', 'fecha_prometida' => '2026-03-12', 'fecha_entrega' => null,         'estado' => 'terminado', 'km' => 31000, 'obs' => 'Revisión de frenos ABS'],
            // Órdenes APROBADAS (mecánico puede continuar)
            ['placa' => 'ATP-5678', 'mecanico' => 'Omar Turcios',    'fecha_ingreso' => '2026-03-12', 'fecha_prometida' => '2026-03-15', 'fecha_entrega' => null,         'estado' => 'aprobado',  'km' => 63000, 'obs' => 'Ruido en caja de dirección'],
            ['placa' => 'AUQ-2345', 'mecanico' => 'José Martínez',   'fecha_ingreso' => '2026-03-14', 'fecha_prometida' => '2026-03-17', 'fecha_entrega' => null,         'estado' => 'aprobado',  'km' => 44000, 'obs' => 'Cambio de embrague'],
            // Órdenes ESPERANDO APROBACIÓN
            ['placa' => 'AVR-6789', 'mecanico' => 'Diego Alvarado',  'fecha_ingreso' => '2026-03-18', 'fecha_prometida' => '2026-03-21', 'fecha_entrega' => null,         'estado' => 'esperando_aprobacion', 'km' => 89000, 'obs' => 'Motor hace ruido al arrancar'],
            ['placa' => 'AWS-3456', 'mecanico' => 'Kevin Moncada',   'fecha_ingreso' => '2026-03-20', 'fecha_prometida' => '2026-03-23', 'fecha_entrega' => null,         'estado' => 'esperando_aprobacion', 'km' => 27000, 'obs' => 'Fuga de líquido de frenos'],
            // Órdenes EN PROCESO
            ['placa' => 'AXT-7890', 'mecanico' => 'Josué Perdomo',   'fecha_ingreso' => '2026-03-22', 'fecha_prometida' => '2026-03-25', 'fecha_entrega' => null,         'estado' => 'en_proceso','km' => 53000, 'obs' => 'Revisión completa pre-viaje'],
            ['placa' => 'AYU-4567', 'mecanico' => 'Nain Cáceres',    'fecha_ingreso' => '2026-03-24', 'fecha_prometida' => '2026-03-27', 'fecha_entrega' => null,         'estado' => 'en_proceso','km' => 36000, 'obs' => 'Cambio de llantas y revisión de suspensión'],
            ['placa' => 'AZV-8901', 'mecanico' => 'Bryan Reconco',   'fecha_ingreso' => '2026-03-26', 'fecha_prometida' => '2026-03-29', 'fecha_entrega' => null,         'estado' => 'en_proceso','km' => 71000, 'obs' => 'Diagnóstico computarizado'],
            // Órdenes PENDIENTES
            ['placa' => 'BAW-5678', 'mecanico' => 'Elvin Suazo',     'fecha_ingreso' => '2026-04-01', 'fecha_prometida' => '2026-04-04', 'fecha_entrega' => null,         'estado' => 'pendiente', 'km' => 48000, 'obs' => 'Cliente trae el vehículo por primera vez'],
            ['placa' => 'BBX-2345', 'mecanico' => null,              'fecha_ingreso' => '2026-04-03', 'fecha_prometida' => '2026-04-06', 'fecha_entrega' => null,         'estado' => 'pendiente', 'km' => 19000, 'obs' => 'Revisión de garantía'],
            ['placa' => 'BCY-6789', 'mecanico' => null,              'fecha_ingreso' => '2026-04-05', 'fecha_prometida' => '2026-04-08', 'fecha_entrega' => null,         'estado' => 'pendiente', 'km' => 82000, 'obs' => 'Falla intermitente en el sistema de inyección'],
        ];

        foreach ($ordenes as $datos) {
            $vehiculo = $vehiculos->where('placa', $datos['placa'])->first();
            if (!$vehiculo) continue;

            $mecanicoId = null;
            if ($datos['mecanico']) {
                $mec = $mecanicos->where('nombre', $datos['mecanico'])->first();
                $mecanicoId = $mec?->id;
            }

            OrdenTrabajo::firstOrCreate(
                [
                    'vehiculo_id'    => $vehiculo->id,
                    'fecha_ingreso'  => $datos['fecha_ingreso'],
                ],
                [
                    'mecanico_id'     => $mecanicoId,
                    'fecha_prometida' => $datos['fecha_prometida'],
                    'fecha_entrega'   => $datos['fecha_entrega'],
                    'estado'          => $datos['estado'],
                    'kilometraje'     => $datos['km'],
                    'observaciones'   => $datos['obs'],
                ]
            );
        }
    }
}
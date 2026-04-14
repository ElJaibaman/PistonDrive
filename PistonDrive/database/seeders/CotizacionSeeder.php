<?php

namespace Database\Seeders;

use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Seeder;

class CotizacionSeeder extends Seeder
{
    public function run(): void
    {
        // Cotizaciones para órdenes entregadas, terminadas, aprobadas y esperando aprobación
        $cotizaciones = [
            // Órdenes ENTREGADAS — cotización aprobada
            ['placa' => 'AEI-1234', 'fecha' => '2026-01-05', 'nota_mec' => 'Pastillas de freno delanteras completamente gastadas, discos con rayaduras profundas',    'nota_sec' => 'Aprobado, proceder con los repuestos indicados', 'estado' => 'aprobada',
             'piezas' => [['Pastillas de freno delanteras', 1, 850.00], ['Discos de freno delanteros', 2, 1200.00], ['Líquido de frenos DOT4', 1, 150.00]],
             'mano'   => [['Cambio de pastillas y discos', 1, 600.00], ['Purgado de frenos', 1, 200.00]]],

            ['placa' => 'AFB-5678', 'fecha' => '2026-01-10', 'nota_mec' => 'Aceite con 8000km de uso, filtro de aceite y aire sucios',                                 'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Aceite motor 5W30 sintético', 4, 180.00], ['Filtro de aceite', 1, 120.00], ['Filtro de aire', 1, 95.00]],
             'mano'   => [['Cambio de aceite', 1, 150.00], ['Revisión general', 1, 100.00]]],

            ['placa' => 'AGC-2345', 'fecha' => '2026-01-15', 'nota_mec' => 'Motor presenta desgaste en bujías y cables de alta tensión',                               'nota_sec' => 'Aprobado, cliente confirmó', 'estado' => 'aprobada',
             'piezas' => [['Bujías NGK (x4)', 4, 120.00], ['Cables de alta tensión', 1, 450.00], ['Bobina de encendido', 1, 850.00]],
             'mano'   => [['Cambio de bujías', 1, 200.00], ['Diagnóstico computarizado', 1, 300.00]]],

            ['placa' => 'AHD-6789', 'fecha' => '2026-01-20', 'nota_mec' => 'Termostato defectuoso, mangueras del radiador con fugas',                                 'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Termostato', 1, 280.00], ['Manguera superior radiador', 1, 195.00], ['Manguera inferior radiador', 1, 175.00], ['Refrigerante', 2, 120.00]],
             'mano'   => [['Cambio de termostato', 1, 350.00], ['Cambio de mangueras', 1, 250.00], ['Revisión de sistema de enfriamiento', 1, 200.00]]],

            ['placa' => 'AIE-3456', 'fecha' => '2026-01-25', 'nota_mec' => 'Pastillas traseras al límite, discos traseros rayados',                                   'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Pastillas de freno traseras', 1, 780.00], ['Discos traseros', 2, 950.00]],
             'mano'   => [['Cambio de frenos traseros', 1, 500.00], ['Ajuste de freno de mano', 1, 150.00]]],

            ['placa' => 'AJF-7890', 'fecha' => '2026-02-01', 'nota_mec' => 'Amortiguadores delanteros con fuga de aceite, bases desgastadas',                        'nota_sec' => 'Aprobado, cliente al tanto', 'estado' => 'aprobada',
             'piezas' => [['Amortiguadores delanteros (par)', 2, 1800.00], ['Bases de amortiguador', 2, 320.00]],
             'mano'   => [['Cambio de amortiguadores', 1, 600.00], ['Alineación', 1, 250.00]]],

            ['placa' => 'AKG-4567', 'fecha' => '2026-02-05', 'nota_mec' => 'Empaque del cárter dañado, causa fuga de aceite',                                        'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Empaque de cárter', 1, 95.00], ['Aceite motor 0W20', 5, 220.00], ['Filtro de aceite', 1, 120.00]],
             'mano'   => [['Cambio de empaque cárter', 1, 400.00], ['Cambio de aceite', 1, 150.00]]],

            ['placa' => 'ALH-8901', 'fecha' => '2026-02-10', 'nota_mec' => 'Correa de distribución al límite de vida útil, tensor y bomba de agua con desgaste',     'nota_sec' => 'Aprobado, trabajo crítico', 'estado' => 'aprobada',
             'piezas' => [['Kit correa de distribución', 1, 2800.00], ['Bomba de agua', 1, 1200.00]],
             'mano'   => [['Cambio correa de distribución', 1, 1500.00], ['Cambio bomba de agua', 1, 600.00]]],

            ['placa' => 'AMI-5678', 'fecha' => '2026-02-15', 'nota_mec' => 'Llantas con desgaste irregular por falta de alineación',                                 'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [],
             'mano'   => [['Alineación de dirección', 1, 350.00], ['Balanceo 4 llantas', 4, 80.00], ['Rotación de llantas', 1, 120.00]]],

            ['placa' => 'ANJ-2345', 'fecha' => '2026-02-20', 'nota_mec' => 'Alternador no carga correctamente, batería se descarga a las 2 horas',                   'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Alternador remanufacturado', 1, 3500.00], ['Batería 65Ah', 1, 1800.00]],
             'mano'   => [['Cambio de alternador', 1, 800.00], ['Revisión sistema eléctrico', 1, 400.00]]],

            ['placa' => 'AOK-6789', 'fecha' => '2026-02-25', 'nota_mec' => 'Transmisión con golpe al cambiar a 2da y 3ra, bajo nivel de aceite ATF',                 'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Aceite ATF Dexron VI', 4, 180.00], ['Filtro de transmisión', 1, 350.00], ['Empaque de cárter transmisión', 1, 120.00]],
             'mano'   => [['Cambio aceite transmisión', 1, 500.00], ['Revisión transmisión', 1, 600.00]]],

            ['placa' => 'APL-3456', 'fecha' => '2026-03-01', 'nota_mec' => 'Batería sulfatada no arranca, revisar bornes y cableado',                                'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Batería 55Ah', 1, 1500.00], ['Borne positivo', 1, 45.00], ['Borne negativo', 1, 45.00]],
             'mano'   => [['Cambio de batería', 1, 150.00], ['Revisión sistema carga', 1, 200.00]]],

            // Órdenes TERMINADAS — cotización aprobada
            ['placa' => 'AQM-7890', 'fecha' => '2026-03-05', 'nota_mec' => 'Compresor de A/C dañado, gas refrigerante agotado',                                     'nota_sec' => 'Aprobado, autorizado', 'estado' => 'aprobada',
             'piezas' => [['Compresor A/C', 1, 5500.00], ['Gas refrigerante R134a', 1, 450.00], ['Filtro de cabina', 1, 180.00]],
             'mano'   => [['Cambio de compresor', 1, 1200.00], ['Carga de gas', 1, 350.00]]],

            ['placa' => 'ARN-4567', 'fecha' => '2026-03-08', 'nota_mec' => 'Amortiguadores traseros con 80000km, recomendable cambio',                              'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Amortiguadores traseros (par)', 2, 1600.00], ['Bases traseras', 2, 280.00]],
             'mano'   => [['Cambio amortiguadores traseros', 1, 550.00], ['Alineación', 1, 250.00]]],

            ['placa' => 'ASO-8901', 'fecha' => '2026-03-10', 'nota_mec' => 'Sensor ABS trasero derecho defectuoso',                                                 'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Sensor ABS trasero', 1, 780.00]],
             'mano'   => [['Cambio sensor ABS', 1, 300.00], ['Diagnóstico ABS', 1, 250.00]]],

            // Órdenes APROBADAS — cotización aprobada, mecánico trabajando
            ['placa' => 'ATP-5678', 'fecha' => '2026-03-12', 'nota_mec' => 'Cremallera de dirección desgastada, holgura excesiva en volante',                       'nota_sec' => 'Aprobado, proceder', 'estado' => 'aprobada',
             'piezas' => [['Cremallera de dirección', 1, 4200.00], ['Aceite de dirección', 1, 120.00]],
             'mano'   => [['Cambio cremallera', 1, 1200.00], ['Alineación', 1, 300.00]]],

            ['placa' => 'AUQ-2345', 'fecha' => '2026-03-14', 'nota_mec' => 'Disco y plato de presión de embrague desgastados, vibración al soltar el pedal',        'nota_sec' => 'Aprobado', 'estado' => 'aprobada',
             'piezas' => [['Kit de embrague completo', 1, 3800.00]],
             'mano'   => [['Cambio de embrague', 1, 2000.00], ['Ajuste de pedal embrague', 1, 150.00]]],

            // Órdenes ESPERANDO APROBACIÓN
            ['placa' => 'AVR-6789', 'fecha' => '2026-03-18', 'nota_mec' => 'Diagnóstico: árbol de levas desgastado, golpe metálico al acelerar',                   'nota_sec' => null, 'estado' => 'pendiente',
             'piezas' => [['Árbol de levas', 1, 12000.00], ['Sellos de válvulas', 8, 45.00], ['Aceite de motor', 5, 180.00]],
             'mano'   => [['Rectificación de cabezote', 1, 3500.00], ['Armado de motor', 1, 2500.00]]],

            ['placa' => 'AWS-3456', 'fecha' => '2026-03-20', 'nota_mec' => 'Fuga en manguera flexible del freno trasero izquierdo, líquido de frenos bajo',        'nota_sec' => null, 'estado' => 'pendiente',
             'piezas' => [['Manguera de freno trasero', 1, 320.00], ['Líquido de frenos DOT4', 1, 150.00]],
             'mano'   => [['Cambio manguera de freno', 1, 250.00], ['Purgado del sistema', 1, 180.00]]],
        ];

        foreach ($cotizaciones as $datos) {
            $orden = OrdenTrabajo::whereHas('vehiculo', fn($q) => $q->where('placa', $datos['placa']))
                ->where('fecha_ingreso', $datos['fecha'])
                ->first();

            if (!$orden) continue;
            if ($orden->cotizacion) continue;

            $cotizacion = Cotizacion::create([
                'orden_id' => $orden->id,
                'nota_mecanico'    => $datos['nota_mec'],
                'nota_secretaria'  => $datos['nota_sec'],
                'estado'           => $datos['estado'],
                'total_piezas'     => 0,
                'total_mano_obra'  => 0,
                'total_general'    => 0,
            ]);

            foreach ($datos['piezas'] as [$desc, $cant, $precio]) {
                CotizacionItem::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'tipo'            => 'pieza',
                    'descripcion'     => $desc,
                    'cantidad'        => $cant,
                    'precio_unitario' => $precio,
                    'subtotal'        => $cant * $precio,
                ]);
            }

            foreach ($datos['mano'] as [$desc, $cant, $precio]) {
                CotizacionItem::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'tipo'            => 'mano_obra',
                    'descripcion'     => $desc,
                    'cantidad'        => $cant,
                    'precio_unitario' => $precio,
                    'subtotal'        => $cant * $precio,
                ]);
            }

            $cotizacion->recalcularTotales();
        }
    }
}
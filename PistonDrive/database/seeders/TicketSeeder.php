<?php

namespace Database\Seeders;

use App\Models\Garantia;
use App\Models\OrdenTrabajo;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            ['placa' => 'AEI-1234', 'fecha' => '2026-01-05', 'metodo' => 'efectivo',      'notas' => 'Cliente pagó en efectivo, satisfecho con el servicio'],
            ['placa' => 'AFB-5678', 'fecha' => '2026-01-10', 'metodo' => 'tarjeta',       'notas' => 'Pago con tarjeta VISA'],
            ['placa' => 'AGC-2345', 'fecha' => '2026-01-15', 'metodo' => 'transferencia', 'notas' => 'Transferencia BAC #TRF20260118'],
            ['placa' => 'AHD-6789', 'fecha' => '2026-01-20', 'metodo' => 'efectivo',      'notas' => null],
            ['placa' => 'AIE-3456', 'fecha' => '2026-01-25', 'metodo' => 'tarjeta',       'notas' => 'Pago con tarjeta Mastercard'],
            ['placa' => 'AJF-7890', 'fecha' => '2026-02-01', 'metodo' => 'efectivo',      'notas' => 'Cliente solicitó factura'],
            ['placa' => 'AKG-4567', 'fecha' => '2026-02-05', 'metodo' => 'transferencia', 'notas' => 'Transferencia Atlántida #TRF20260208'],
            ['placa' => 'ALH-8901', 'fecha' => '2026-02-10', 'metodo' => 'efectivo',      'notas' => 'Trabajo crítico completado exitosamente'],
            ['placa' => 'AMI-5678', 'fecha' => '2026-02-15', 'metodo' => 'tarjeta',       'notas' => null],
            ['placa' => 'ANJ-2345', 'fecha' => '2026-02-20', 'metodo' => 'efectivo',      'notas' => 'Cliente satisfecho, regresará para mantenimiento'],
            ['placa' => 'AOK-6789', 'fecha' => '2026-02-25', 'metodo' => 'transferencia', 'notas' => 'Transferencia Ficohsa #TRF20260228'],
            ['placa' => 'APL-3456', 'fecha' => '2026-03-01', 'metodo' => 'efectivo',      'notas' => null],
        ];

        foreach ($tickets as $datos) {
            $orden = OrdenTrabajo::whereHas('vehiculo', fn($q) => $q->where('placa', $datos['placa']))
                ->where('fecha_ingreso', $datos['fecha'])
                ->with('cotizacion')
                ->first();

            if (!$orden || $orden->ticket) continue;

            $cotizacion    = $orden->cotizacion;
            $totalPiezas   = $cotizacion ? $cotizacion->total_piezas    : 0;
            $totalManoObra = $cotizacion ? $cotizacion->total_mano_obra : 0;
            $totalGeneral  = $cotizacion ? $cotizacion->total_general   : 0;

            $ticket = Ticket::create([
                'orden_id'       => $orden->id,
                'total_piezas'   => $totalPiezas,
                'total_mano_obra'=> $totalManoObra,
                'total'          => $totalGeneral,
                'metodo_pago'    => $datos['metodo'],
                'notas'          => $datos['notas'],
            ]);

            $fechaEntrega     = Carbon::parse($orden->fecha_entrega ?? $orden->fecha_prometida);
            $fechaVencimiento = $fechaEntrega->copy()->addMonth();

            Garantia::create([
                'ticket_id'         => $ticket->id,
                'fecha_inicio'      => $fechaEntrega->toDateString(),
                'fecha_vencimiento' => $fechaVencimiento->toDateString(),
                'estado'            => $fechaVencimiento->isPast() ? 'vencida' : 'vigente',
                'descripcion'       => 'Garantía de 1 mes por servicio realizado',
            ]);
        }
    }
}
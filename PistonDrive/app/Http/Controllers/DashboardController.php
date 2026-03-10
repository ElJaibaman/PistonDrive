<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Garantia;
use App\Models\Ticket;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $vehiculosActivos = OrdenTrabajo::with(['vehiculo.cliente', 'mecanico'])
            ->whereIn('estado', ['pendiente', 'en_proceso', 'esperando_aprobacion', 'aprobado', 'terminado'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($o) => [
                ...$o->toArray(),
                'estado_config' => OrdenTrabajo::$estados[$o->estado],
            ]);

        $ganancias = Ticket::select(
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as mes"),
                DB::raw('SUM(total) as ingreso_total'),
                DB::raw('SUM(total_piezas) as costo_repuestos'),
                DB::raw('COUNT(*) as tickets')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $garantiasVigentes = Garantia::with(['ticket.orden.vehiculo.cliente'])
            ->where('estado', 'vigente')
            ->orderBy('fecha_vencimiento')
            ->get()
            ->map(fn($g) => [
                ...$g->toArray(),
                'dias_restantes' => $g->dias_restantes,
            ]);

        $cotizacionesPendientes = Cotizacion::with(['orden.vehiculo.cliente', 'orden.mecanico'])
            ->where('estado', 'pendiente')
            ->orderByDesc('created_at')
            ->get();

        $resumen = [
            'pendientes'              => OrdenTrabajo::where('estado', 'pendiente')->count(),
            'en_proceso'              => OrdenTrabajo::where('estado', 'en_proceso')->count(),
            'esperando_aprobacion'    => OrdenTrabajo::where('estado', 'esperando_aprobacion')->count(),
            'terminados'              => OrdenTrabajo::where('estado', 'terminado')->count(),
            'cotizaciones_pendientes' => Cotizacion::where('estado', 'pendiente')->count(),
        ];

        return Inertia::render('Dashboard/Dashboard', [
            'vehiculosActivos'       => $vehiculosActivos,
            'ganancias'              => $ganancias,
            'garantiasVigentes'      => $garantiasVigentes,
            'cotizacionesPendientes' => $cotizacionesPendientes,
            'resumen'                => $resumen,
        ]);
    }
}
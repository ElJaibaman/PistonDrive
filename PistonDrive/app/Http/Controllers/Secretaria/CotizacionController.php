<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CotizacionController extends Controller
{
    public function index(): Response
    {
        $cotizaciones = Cotizacion::with([
                'orden.vehiculo.cliente',
                'orden.mecanico',
            ])
            ->orderByRaw("FIELD(estado,'pendiente','aprobada','rechazada')")
            ->orderByDesc('created_at')
            ->paginate(15);

        return Inertia::render('Secretaria/Cotizaciones/Index', [
            'cotizaciones' => $cotizaciones,
        ]);
    }

    public function show(Cotizacion $cotizacion): Response
    {
        $cotizacion->load([
            'orden.vehiculo.cliente',
            'orden.mecanico',
            'piezas',
            'manoObra',
        ]);

        return Inertia::render('Secretaria/Cotizaciones/Show', [
            'cotizacion' => $cotizacion,
        ]);
    }

    public function aprobar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'nota_secretaria' => 'nullable|string|max:500',
        ]);

        $cotizacion->update([
            'estado'          => 'aprobada',
            'nota_secretaria' => $request->nota_secretaria,
        ]);

        $cotizacion->orden->update(['estado' => 'aprobado']);

        return redirect()->route('secretaria.cotizaciones.index')
            ->with('success', 'Cotización aprobada. El mecánico puede continuar.');
    }

    public function rechazar(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'nota_secretaria' => 'required|string|max:500',
        ]);

        $cotizacion->update([
            'estado'          => 'rechazada',
            'nota_secretaria' => $request->nota_secretaria,
        ]);

        $cotizacion->orden->update(['estado' => 'rechazado']);

        return redirect()->route('secretaria.cotizaciones.index')
            ->with('success', 'Cotización rechazada. El mecánico fue notificado.');
    }
}
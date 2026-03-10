<?php

namespace App\Http\Controllers\Mecanico;

use App\Http\Controllers\Controller;
use App\Models\OrdenTrabajo;
use App\Models\Cotizacion;
use App\Models\CotizacionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MecanicoOrdenController extends Controller
{
    private function serviciosManoObra(): array
    {
        return [
            'Cambio de llantas',
            'Frenos',
            'Revisión general',
            'Aire acondicionado',
            'Sistema eléctrico',
            'Suspensión',
            'Alineación y balanceo',
            'Cambio de filtros',
            'Diagnóstico computarizado',
        ];
    }

    private function mecanicoAutenticado()
    {
        $mecanico = Auth::user()->mecanico;
        abort_unless($mecanico, 403, 'Tu usuario no tiene un mecánico vinculado.');
        return $mecanico;
    }

    private function autorizarOrden(OrdenTrabajo $orden): void
    {
        $mecanico = $this->mecanicoAutenticado();
        abort_unless($orden->mecanico_id === $mecanico->id, 403, 'No tienes acceso a esta orden.');
    }

    public function index(): Response
    {
        $mecanico = $this->mecanicoAutenticado();

        $ordenes = OrdenTrabajo::with(['vehiculo.cliente', 'cotizacion'])
            ->where('mecanico_id', $mecanico->id)
            ->whereNotIn('estado', ['entregado'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($o) => [
                ...$o->toArray(),
                'estado_config' => OrdenTrabajo::$estados[$o->estado],
            ]);

        return Inertia::render('Mecanico/Ordenes/Index', [
            'ordenes'  => $ordenes,
            'mecanico' => $mecanico,
        ]);
    }

    public function show(OrdenTrabajo $orden): Response
    {
        $this->autorizarOrden($orden);
        $orden->load(['vehiculo.cliente', 'cotizacion.items']);

        return Inertia::render('Mecanico/Ordenes/Show', [
            'orden'             => $orden,
            'estadoConfig'      => OrdenTrabajo::$estados[$orden->estado],
            'estadosPermitidos' => OrdenTrabajo::$estadosMecanico,
            'todosEstados'      => OrdenTrabajo::$estados,
        ]);
    }

    public function cambiarEstado(Request $request, OrdenTrabajo $orden)
    {
        $this->autorizarOrden($orden);

        $request->validate([
            'estado' => ['required', 'in:' . implode(',', OrdenTrabajo::$estadosMecanico)],
        ]);

        $orden->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function createCotizacion(OrdenTrabajo $orden): Response
    {
        $this->autorizarOrden($orden);

        abort_if(
            $orden->cotizacion && $orden->cotizacion->estado === 'pendiente',
            403,
            'Ya tienes una cotización pendiente de revisión.'
        );

        $orden->load(['vehiculo.cliente', 'cotizacion.items']);

        return Inertia::render('Mecanico/Ordenes/Cotizacion', [
            'orden'             => $orden,
            'serviciosManoObra' => $this->serviciosManoObra(),
        ]);
    }

    public function storeCotizacion(Request $request, OrdenTrabajo $orden)
    {
        $this->autorizarOrden($orden);

        $request->validate([
            'nota_mecanico'               => 'nullable|string|max:500',
            'piezas'                      => 'array',
            'piezas.*.descripcion'        => 'required_with:piezas|string|max:200',
            'piezas.*.cantidad'           => 'required_with:piezas|integer|min:1',
            'piezas.*.precio_unitario'    => 'required_with:piezas|numeric|min:0',
            'mano_obra'                   => 'required|array|min:1',
            'mano_obra.*.descripcion'     => 'required|string|max:200',
            'mano_obra.*.cantidad'        => 'required|integer|min:1',
            'mano_obra.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $orden) {
            // Eliminar cotización rechazada anterior si existe
            $orden->cotizacion?->delete();

            $cotizacion = Cotizacion::create([
                'orden_id'      => $orden->id,
                'estado'        => 'pendiente',
                'nota_mecanico' => $request->nota_mecanico,
            ]);

            foreach ($request->piezas ?? [] as $pieza) {
                CotizacionItem::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'tipo'            => 'pieza',
                    'descripcion'     => $pieza['descripcion'],
                    'cantidad'        => $pieza['cantidad'],
                    'precio_unitario' => $pieza['precio_unitario'],
                ]);
            }

            foreach ($request->mano_obra as $mo) {
                CotizacionItem::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'tipo'            => 'mano_obra',
                    'descripcion'     => $mo['descripcion'],
                    'cantidad'        => $mo['cantidad'],
                    'precio_unitario' => $mo['precio_unitario'],
                ]);
            }

            $cotizacion->recalcularTotales();

            $orden->update(['estado' => 'esperando_aprobacion']);
        });

        return redirect()->route('mecanico.ordenes.show', $orden)
            ->with('success', 'Cotización enviada. Esperando aprobación de la secretaria.');
    }
}

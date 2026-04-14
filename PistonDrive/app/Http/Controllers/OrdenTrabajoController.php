<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\Mecanico;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdenTrabajoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Ordenes/Index', [
            'ordenes'        => OrdenTrabajo::with(['vehiculo.cliente', 'mecanico', 'cotizacion'])->orderByDesc('created_at')->paginate(10),
            'estados'        => OrdenTrabajo::$estados,
            'totalOrdenes'   => OrdenTrabajo::count(),
            'totalPendientes'=> OrdenTrabajo::where('estado', 'pendiente')->count(),
            'totalEnProceso' => OrdenTrabajo::where('estado', 'en_proceso')->count(),
            'totalEspAprobacion' => OrdenTrabajo::where('estado', 'esperando_aprobacion')->count(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Ordenes/Form', [
            'orden'     => null,
            'vehiculos' => Vehiculo::with('cliente')->orderBy('marca')->get(),
            'mecanicos' => Mecanico::where('activo', true)->orderBy('nombre')->get(),
            'estados'   => OrdenTrabajo::$estados,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehiculo_id'     => 'required|exists:vehiculos,id',
            'mecanico_id'     => 'nullable|exists:mecanicos,id',
            'fecha_ingreso'   => 'required|date',
            'fecha_prometida' => 'nullable|date|after_or_equal:fecha_ingreso',
            'estado'          => 'required|in:pendiente,en_proceso',
            'kilometraje'     => 'nullable|integer|min:0',
            'observaciones'   => 'nullable|string',
        ]);

        OrdenTrabajo::create($data);

        return redirect()->route('ordenes.index')
            ->with('success', 'Orden de trabajo creada.');
    }

    public function show(OrdenTrabajo $orden): Response
    {
        $orden->load([
            'vehiculo.cliente', 'mecanico',
            'detalles', 'ticket.garantia',
            'cotizacion.items',
        ]);

        return Inertia::render('Ordenes/Show', [
            'orden'   => $orden,
            'estados' => OrdenTrabajo::$estados,
        ]);
    }

    public function edit(OrdenTrabajo $orden): Response
    {
        return Inertia::render('Ordenes/Form', [
            'orden'     => $orden,
            'vehiculos' => Vehiculo::with('cliente')->orderBy('marca')->get(),
            'mecanicos' => Mecanico::where('activo', true)->orderBy('nombre')->get(),
            'estados'   => OrdenTrabajo::$estados,
        ]);
    }

    public function update(Request $request, OrdenTrabajo $orden)
    {
        $data = $request->validate([
            'vehiculo_id'     => 'required|exists:vehiculos,id',
            'mecanico_id'     => 'nullable|exists:mecanicos,id',
            'fecha_ingreso'   => 'required|date',
            'fecha_prometida' => 'nullable|date',
            'fecha_entrega'   => 'nullable|date',
            'estado'          => 'required|in:' . implode(',', array_keys(OrdenTrabajo::$estados)),
            'kilometraje'     => 'nullable|integer|min:0',
            'observaciones'   => 'nullable|string',
        ]);

        $orden->update($data);

        return redirect()->route('ordenes.index')
            ->with('success', 'Orden actualizada.');
    }

    public function destroy(OrdenTrabajo $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index')
            ->with('success', 'Orden eliminada.');
    }
}
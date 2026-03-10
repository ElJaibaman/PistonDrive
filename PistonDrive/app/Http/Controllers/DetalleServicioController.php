<?php

namespace App\Http\Controllers;

use App\Models\DetalleServicio;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DetalleServicioController extends Controller
{
    /**
     * Lista los detalles de una orden específica.
     */
    public function index(OrdenTrabajo $orden): Response
    {
        $orden->load(['vehiculo.cliente', 'mecanico', 'detalles']);

        return Inertia::render('Ordenes/Detalles/Index', [
            'orden'    => $orden,
            'detalles' => $orden->detalles,
        ]);
    }

    /**
     * Guarda un nuevo detalle de servicio en una orden.
     */
    public function store(Request $request, OrdenTrabajo $orden)
    {
        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
            'costo'       => 'required|numeric|min:0',
        ]);

        $orden->detalles()->create($data);

        return back()->with('success', 'Detalle agregado correctamente.');
    }

    /**
     * Actualiza un detalle existente.
     */
    public function update(Request $request, OrdenTrabajo $orden, DetalleServicio $detalle)
    {
        // Verificar que el detalle pertenece a la orden
        abort_unless($detalle->orden_id === $orden->id, 403, 'Este detalle no pertenece a la orden.');

        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
            'costo'       => 'required|numeric|min:0',
        ]);

        $detalle->update($data);

        return back()->with('success', 'Detalle actualizado.');
    }

    /**
     * Elimina un detalle de servicio.
     */
    public function destroy(OrdenTrabajo $orden, DetalleServicio $detalle)
    {
        abort_unless($detalle->orden_id === $orden->id, 403, 'Este detalle no pertenece a la orden.');

        $detalle->delete();

        return back()->with('success', 'Detalle eliminado.');
    }
}
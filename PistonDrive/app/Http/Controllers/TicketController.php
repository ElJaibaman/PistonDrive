<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\OrdenTrabajo;
use App\Models\Garantia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index(): Response
    {
        $tickets = Ticket::with(['orden.vehiculo.cliente', 'garantia'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(Request $request): Response
    {
        $orden = OrdenTrabajo::with(['vehiculo.cliente', 'mecanico', 'cotizacion'])
            ->findOrFail($request->orden_id);

        return Inertia::render('Tickets/Create', [
            'orden' => $orden,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'orden_id'           => 'required|exists:ordenes_trabajo,id|unique:tickets,orden_id',
            'subtotal_servicios' => 'required|numeric|min:0',
            'subtotal_repuestos' => 'required|numeric|min:0',
            'total'              => 'required|numeric|min:0',
            'metodo_pago'        => 'required|in:efectivo,tarjeta,transferencia',
            'notas'              => 'nullable|string',
        ]);

        $ticket = Ticket::create($data);

        Garantia::create([
            'ticket_id'         => $ticket->id,
            'fecha_inicio'      => Carbon::today(),
            'fecha_vencimiento' => Carbon::today()->addMonth(),
            'estado'            => 'vigente',
        ]);

        OrdenTrabajo::find($data['orden_id'])->update([
            'estado'        => 'entregado',
            'fecha_entrega' => Carbon::today(),
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket generado con garantía de 1 mes.');
    }

    public function show(Ticket $ticket): Response
    {
        $ticket->load([
            'orden.vehiculo.cliente',
            'orden.detalles',
            'orden.mecanico',
            'orden.cotizacion.items',
            'garantia',
        ]);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
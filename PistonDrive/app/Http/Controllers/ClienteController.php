<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    public function index(): Response
    {
        $clientes = Cliente::withCount('vehiculos')
            ->orderBy('nombre')
            ->paginate(15);

        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clientes/Form', ['cliente' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'numero_identidad' => 'nullable|string|max:20|unique:clientes,numero_identidad',
            'procedencia'      => 'nullable|string|max:100',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email|max:100',
        ]);

        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): Response
    {
        return Inertia::render('Clientes/Form', ['cliente' => $cliente]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'numero_identidad' => 'nullable|string|max:20|unique:clientes,numero_identidad,' . $cliente->id,
            'procedencia'      => 'nullable|string|max:100',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email|max:100',
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoController extends Controller
{
    public function index(): Response
    {
        $vehiculos = Vehiculo::with('cliente')
            ->orderBy('marca')
            ->paginate(15);

        return Inertia::render('Vehiculos/Index', [
            'vehiculos' => $vehiculos,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Vehiculos/Form', [
            'vehiculo' => null,
            'clientes' => Cliente::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'    => 'required|exists:clientes,id',
            'placa'         => 'nullable|string|max:20',
            'marca'         => 'required|string|max:60',
            'modelo'        => 'required|string|max:60',
            'anio'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color'         => 'nullable|string|max:40',
            'observaciones' => 'nullable|string',
        ]);

        Vehiculo::create($data);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado correctamente.');
    }

    public function edit(Vehiculo $vehiculo): Response
    {
        return Inertia::render('Vehiculos/Form', [
            'vehiculo' => $vehiculo,
            'clientes' => Cliente::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $data = $request->validate([
            'cliente_id'    => 'required|exists:clientes,id',
            'placa'         => 'nullable|string|max:20',
            'marca'         => 'required|string|max:60',
            'modelo'        => 'required|string|max:60',
            'anio'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color'         => 'nullable|string|max:40',
            'observaciones' => 'nullable|string',
        ]);

        $vehiculo->update($data);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class MecanicoController extends Controller
{
    public function index(): Response
    {
        $mecanicos = Mecanico::withCount('ordenes')
            ->orderBy('nombre')
            ->paginate(15);

        return Inertia::render('Mecanicos/Index', [
            'mecanicos' => $mecanicos,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Mecanicos/Form', ['mecanico' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'activo'   => 'boolean',
            // Datos opcionales para crear usuario al mecánico
            'crear_usuario' => 'boolean',
            'email'         => 'nullable|required_if:crear_usuario,true|email|unique:users,email',
            'password'      => 'nullable|required_if:crear_usuario,true|string|min:8',
        ]);

        $mecanico = Mecanico::create([
            'nombre'   => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
            'activo'   => $data['activo'] ?? true,
        ]);

        // Si se pidió crear usuario vinculado al mecánico
        if (!empty($data['crear_usuario'])) {
            User::create([
                'name'        => $mecanico->nombre,
                'email'       => $data['email'],
                'password'    => Hash::make($data['password']),
                'rol'         => 'mecanico',
                'mecanico_id' => $mecanico->id,
            ]);
        }

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecánico registrado correctamente.');
    }

    public function edit(Mecanico $mecanico): Response
    {
        $mecanico->load('usuario');

        return Inertia::render('Mecanicos/Form', [
            'mecanico' => $mecanico,
        ]);
    }

    public function update(Request $request, Mecanico $mecanico)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'activo'   => 'boolean',
        ]);

        $mecanico->update($data);

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecánico actualizado correctamente.');
    }

    public function destroy(Mecanico $mecanico)
    {
        // Verificar que no tenga órdenes activas antes de eliminar
        $ordenesActivas = $mecanico->ordenes()
            ->whereNotIn('estado', ['entregado'])
            ->count();

        if ($ordenesActivas > 0) {
            return back()->with('error', 'No se puede eliminar: el mecánico tiene órdenes activas.');
        }

        $mecanico->delete();

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecánico eliminado.');
    }

    /**
     * Activa o desactiva un mecánico rápidamente desde la lista.
     */
    public function toggleActivo(Mecanico $mecanico)
    {
        $mecanico->update(['activo' => !$mecanico->activo]);

        $estado = $mecanico->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Mecánico {$estado} correctamente.");
    }
}
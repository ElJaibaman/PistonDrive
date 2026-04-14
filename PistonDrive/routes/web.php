<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\DetalleServicioController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mecanico\MecanicoOrdenController;
use App\Http\Controllers\Secretaria\CotizacionController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // ── SECRETARIA / ENCARGADO / ADMIN ────────────────────────────────────────
    Route::middleware(['rol:secretaria,encargado,admin'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Clientes
        Route::resource('clientes', ClienteController::class);

        // Vehículos
        Route::resource('vehiculos', VehiculoController::class);

        // Mecánicos
        Route::resource('mecanicos', MecanicoController::class);
        Route::patch('mecanicos/{mecanico}/toggle-activo', [MecanicoController::class, 'toggleActivo'])
            ->name('mecanicos.toggle-activo');

        // Órdenes de trabajo
        Route::resource('ordenes', OrdenTrabajoController::class)
            ->parameters(['ordenes' => 'orden']);

        // Detalles de servicio (anidados bajo órdenes)
        Route::prefix('ordenes/{orden}/detalles')
            ->name('ordenes.detalles.')
            ->group(function () {
                Route::get('/',            [DetalleServicioController::class, 'index'])->name('index');
                Route::post('/',           [DetalleServicioController::class, 'store'])->name('store');
                Route::patch('/{detalle}', [DetalleServicioController::class, 'update'])->name('update');
                Route::delete('/{detalle}',[DetalleServicioController::class, 'destroy'])->name('destroy');
            });

        // Tickets — create DEBE ir antes de {ticket}
        Route::get('tickets',          [TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/create',   [TicketController::class, 'create'])->name('tickets.create');
        Route::post('tickets',         [TicketController::class, 'store'])->name('tickets.store');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

        // Cotizaciones (revisión por secretaria)
        Route::prefix('secretaria/cotizaciones')
            ->name('secretaria.cotizaciones.')
            ->group(function () {
                Route::get('/',                        [CotizacionController::class, 'index'])->name('index');
                Route::get('/{cotizacion}',            [CotizacionController::class, 'show'])->name('show');
                Route::patch('/{cotizacion}/aprobar',  [CotizacionController::class, 'aprobar'])->name('aprobar');
                Route::patch('/{cotizacion}/rechazar', [CotizacionController::class, 'rechazar'])->name('rechazar');
            });
    });

    // ── MECÁNICO ──────────────────────────────────────────────────────────────
    Route::middleware(['rol:mecanico'])
        ->prefix('mecanico')
        ->name('mecanico.')
        ->group(function () {
            Route::get('/ordenes',                     [MecanicoOrdenController::class, 'index'])->name('ordenes.index');
            Route::get('/ordenes/{orden}',             [MecanicoOrdenController::class, 'show'])->name('ordenes.show');
            Route::patch('/ordenes/{orden}/estado',    [MecanicoOrdenController::class, 'cambiarEstado'])->name('ordenes.estado');
            Route::get('/ordenes/{orden}/cotizacion',  [MecanicoOrdenController::class, 'createCotizacion'])->name('cotizacion.create');
            Route::post('/ordenes/{orden}/cotizacion', [MecanicoOrdenController::class, 'storeCotizacion'])->name('cotizacion.store');
        });
});
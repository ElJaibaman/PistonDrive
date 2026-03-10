<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Vehiculo;
use App\Models\Mecanico;
use App\Models\DetalleServicio;
use App\Models\Ticket;
use App\Models\Cotizacion;


class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'vehiculo_id', 'mecanico_id', 'fecha_ingreso', 'fecha_prometida',
        'fecha_entrega', 'estado', 'kilometraje', 'observaciones',
    ];

    protected $casts = [
        'fecha_ingreso'   => 'date',
        'fecha_prometida' => 'date',
        'fecha_entrega'   => 'date',
    ];

    public static array $estados = [
        'pendiente'            => ['label' => 'Pendiente',            'color' => 'grey'],
        'en_proceso'           => ['label' => 'En proceso',           'color' => 'blue'],
        'esperando_aprobacion' => ['label' => 'Esperando aprobación', 'color' => 'orange'],
        'aprobado'             => ['label' => 'Aprobado',             'color' => 'green'],
        'rechazado'            => ['label' => 'Rechazado',            'color' => 'red'],
        'terminado'            => ['label' => 'Terminado',            'color' => 'teal'],
        'entregado'            => ['label' => 'Entregado',            'color' => 'purple'],
    ];

    public static array $estadosMecanico = ['en_proceso', 'esperando_aprobacion', 'terminado'];

    public function vehiculo(): BelongsTo   { return $this->belongsTo(Vehiculo::class, 'vehiculo_id'); }
    public function mecanico(): BelongsTo   { return $this->belongsTo(Mecanico::class, 'mecanico_id'); }
    public function detalles(): HasMany { return $this->hasMany(DetalleServicio::class, 'orden_trabajo_id');}
    public function ticket(): HasOne        { return $this->hasOne(Ticket::class, 'orden_id'); }
    public function cotizacion(): HasOne    { return $this->hasOne(Cotizacion::class, 'orden_id'); }
}
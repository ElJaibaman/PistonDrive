<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrdenTrabajo;
use App\Models\Cliente;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $fillable = ['cliente_id', 'placa', 'marca', 'modelo', 'anio', 'color', 'observaciones'];

    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class, 'cliente_id'); }
    public function ordenes(): HasMany   { return $this->hasMany(OrdenTrabajo::class, 'vehiculo_id'); }
}
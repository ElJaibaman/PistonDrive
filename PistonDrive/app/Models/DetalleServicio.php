<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrdenTrabajo;

class DetalleServicio extends Model
{
    protected $table    = 'detalles_servicio';
    protected $fillable = ['orden_id', 'descripcion', 'costo'];
    protected $casts    = ['costo' => 'decimal:2'];

   public function orden(): BelongsTo { return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cotizacion;


class CotizacionItem extends Model
{
    protected $table    = 'cotizacion_items';
    protected $fillable = ['cotizacion_id', 'tipo', 'descripcion', 'cantidad', 'precio_unitario', 'subtotal'];
    protected $casts    = ['precio_unitario' => 'decimal:2', 'subtotal' => 'decimal:2'];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->subtotal = $item->cantidad * $item->precio_unitario;
        });
    }

    public function cotizacion(): BelongsTo { return $this->belongsTo(Cotizacion::class, 'cotizacion_id'); }
}
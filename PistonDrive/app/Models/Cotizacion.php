<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OrdenTrabajo;
use App\Models\CotizacionItem;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'orden_id', 'estado', 'nota_mecanico',
        'nota_secretaria', 'total_piezas', 'total_mano_obra', 'total_general',
    ];

    protected $casts = [
        'total_piezas'    => 'decimal:2',
        'total_mano_obra' => 'decimal:2',
        'total_general'   => 'decimal:2',
    ];

    public function orden(): BelongsTo    { return $this->belongsTo(OrdenTrabajo::class, 'orden_id'); }
    public function items(): HasMany      { return $this->hasMany(CotizacionItem::class, 'cotizacion_id'); }
    public function piezas(): HasMany     { return $this->hasMany(CotizacionItem::class, 'cotizacion_id')->where('tipo', 'pieza'); }
    public function manoObra(): HasMany   { return $this->hasMany(CotizacionItem::class, 'cotizacion_id')->where('tipo', 'mano_obra'); }

    public function recalcularTotales(): void
    {
        $this->total_piezas    = $this->piezas()->sum('subtotal');
        $this->total_mano_obra = $this->manoObra()->sum('subtotal');
        $this->total_general   = $this->total_piezas + $this->total_mano_obra;
        $this->save();
    }
}
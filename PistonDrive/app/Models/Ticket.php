<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\OrdenTrabajo;
use App\Models\Garantia;

class Ticket extends Model
{
    protected $table    = 'tickets';
    protected $fillable = ['orden_id', 'total_piezas', 'total_mano_obra', 'total', 'metodo_pago', 'notas'];
    protected $casts    = ['total_piezas' => 'decimal:2', 'total_mano_obra' => 'decimal:2', 'total' => 'decimal:2'];

    public function orden(): BelongsTo  { return $this->belongsTo(OrdenTrabajo::class, 'orden_id'); }
    public function garantia(): HasOne  { return $this->hasOne(Garantia::class, 'ticket_id'); }
}
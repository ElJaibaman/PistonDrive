<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Ticket;

class Garantia extends Model
{
    protected $table    = 'garantias';
    protected $fillable = ['ticket_id', 'fecha_inicio', 'fecha_vencimiento', 'estado', 'descripcion'];
    protected $casts    = ['fecha_inicio' => 'date', 'fecha_vencimiento' => 'date'];

    public function getDiasRestantesAttribute(): int
    {
        return max(0, now()->diffInDays($this->fecha_vencimiento, false));
    }

    public function ticket(): BelongsTo { return $this->belongsTo(Ticket::class, 'ticket_id'); }
}
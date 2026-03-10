<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'mecanico_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(Mecanico::class, 'mecanico_id');
    }

    // ── Helpers de rol ────────────────────────────────────────────────────────

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esSecretaria(): bool
    {
        return in_array($this->rol, ['secretaria', 'encargado', 'admin']);
    }

    public function esMecanico(): bool
    {
        return $this->rol === 'mecanico';
    }
}
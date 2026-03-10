<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\OrdenTrabajo;
use App\Models\User;

class Mecanico extends Model
{
    protected $table = 'mecanicos';
    protected $fillable = ['nombre', 'telefono', 'activo'];
    protected $casts   = ['activo' => 'boolean'];

    public function ordenes(): HasMany { return $this->hasMany(OrdenTrabajo::class, 'mecanico_id'); }
    public function usuario(): HasOne  { return $this->hasOne(User::class, 'mecanico_id'); }
}

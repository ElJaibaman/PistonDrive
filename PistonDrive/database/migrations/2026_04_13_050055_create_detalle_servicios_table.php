<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_trabajo_id')
                  ->constrained('ordenes_trabajo')
                  ->cascadeOnDelete();
            $table->string('descripcion');           // Descripción del servicio realizado
            $table->decimal('costo', 10, 2)->default(0.00); // Costo del servicio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_servicio');
    }
};
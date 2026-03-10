<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            // Una orden solo puede tener una cotización activa (unique)
            $table->foreignId('orden_id')
                  ->unique()
                  ->constrained('ordenes_trabajo')
                  ->cascadeOnDelete();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])
                  ->default('pendiente');
            $table->text('nota_mecanico')->nullable();
            $table->text('nota_secretaria')->nullable();
            // Totales calculados y almacenados para historial
            $table->decimal('total_piezas', 10, 2)->default(0.00);
            $table->decimal('total_mano_obra', 10, 2)->default(0.00);
            $table->decimal('total_general', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};

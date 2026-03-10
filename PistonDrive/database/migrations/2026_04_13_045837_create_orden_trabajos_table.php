<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')
                  ->constrained('vehiculos')
                  ->restrictOnDelete();
            $table->foreignId('mecanico_id')
                  ->nullable()
                  ->constrained('mecanicos')
                  ->nullOnDelete();
            $table->date('fecha_ingreso');
            $table->date('fecha_prometida')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->enum('estado', [
                'pendiente',            // Recién creada por la secretaria
                'en_proceso',           // Mecánico trabajando
                'esperando_aprobacion', // Mecánico envió cotización
                'aprobado',             // Secretaria aprobó cotización
                'rechazado',            // Secretaria rechazó cotización
                'terminado',            // Mecánico terminó el trabajo
                'entregado',            // Secretaria generó ticket y entregó
            ])->default('pendiente');
            $table->unsignedInteger('kilometraje')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};

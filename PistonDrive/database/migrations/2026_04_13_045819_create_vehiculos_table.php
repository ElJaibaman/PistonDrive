<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->restrictOnDelete();
            $table->string('placa', 20)->nullable();
            $table->string('marca', 60);
            $table->string('modelo', 60);
            // Corregido: unsignedSmallInteger en lugar de year()
            // year() de MySQL solo acepta 1901-2155 y Eloquent lo serializa mal
            $table->unsignedSmallInteger('anio')->nullable();
            $table->string('color', 40)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};

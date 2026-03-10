<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')
                  ->unique()
                  ->constrained('ordenes_trabajo')
                  ->restrictOnDelete();
            // Total que viene de la cotización aprobada
            $table->decimal('total_piezas', 10, 2)->default(0.00);
            $table->decimal('total_mano_obra', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])
                  ->default('efectivo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

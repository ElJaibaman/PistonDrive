<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'secretaria', 'encargado', 'mecanico'])
                  ->default('secretaria')
                  ->after('email');
            $table->foreignId('mecanico_id')
                  ->nullable()
                  ->after('rol')
                  ->constrained('mecanicos')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['mecanico_id']);
            $table->dropColumn(['rol', 'mecanico_id']);
        });
    }
};
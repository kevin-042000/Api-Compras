<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Columna de clave primaria autoincremental
            $table->string('name')->unique(); // Nombre de la categoría, único para cada categoría
            $table->string('description')->nullable(); // Descripción de la categoría (puede ser nula)
            $table->timestamps(); // Columnas para registrar la fecha y hora de creación y actualización del registro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

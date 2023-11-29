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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // Columna de clave primaria autoincremental
            $table->decimal('subtotal', 8, 2); // Subtotal de la compra con 8 dígitos en total, 2 decimales
            $table->decimal('total'); // Total de la compra (presumiblemente sin límite de dígitos)
            $table->timestamps(); // Columnas para registrar la fecha y hora de creación y actualización del registro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

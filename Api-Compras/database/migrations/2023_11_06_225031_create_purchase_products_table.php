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
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id(); // Columna de clave primaria autoincremental
            $table->unsignedBigInteger('purchase_id'); // Clave foránea para la relación con 'purchases'
            $table->unsignedBigInteger('product_id'); // Clave foránea para la relación con 'products'
            $table->decimal('price', 8, 2); // Precio del producto con 8 dígitos en total, 2 decimales
            $table->integer('quantity'); // Cantidad de productos en la compra
            $table->decimal('subtotal', 8, 2); // Subtotal de la compra con 8 dígitos en total, 2 decimales
            $table->timestamps(); // Columnas para registrar la fecha y hora de creación y actualización del registro

            // Definimos las claves foráneas para mantener la integridad referencial
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_products');
    }
};

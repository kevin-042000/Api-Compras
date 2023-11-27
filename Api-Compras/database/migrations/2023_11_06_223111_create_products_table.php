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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Columna de clave primaria autoincremental
            $table->string('name')->unique(); // Nombre del producto, único para cada producto
            $table->string('description')->nullable(); // Descripción del producto (puede ser nulo)
            $table->decimal('price', 8, 2); // Precio del producto con 8 dígitos en total, 2 decimales
            $table->integer('quantity_available'); // Cantidad disponible del producto
            $table->unsignedBigInteger('category_id'); // Clave foránea para la relación con 'categories'
            $table->unsignedBigInteger('brand_id'); // Clave foránea para la relación con 'brands'
            $table->timestamps(); // Columnas para registrar la fecha y hora de creación y actualización del registro
            
            // Definimos las claves foráneas para mantener la integridad referencial
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

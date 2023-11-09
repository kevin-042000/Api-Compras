<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'quantity_available', 'category_id', 'brand_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class); // Un Producto pertenece a una única categoría.
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class); // Un Producto pertenece a una única marca.
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
            ->withPivot('price', 'quantity', 'subtotal')
            ->withTimestamps(); // Un Producto puede pertenecer a varias instancias de Compra, y viceversa.
    }
}


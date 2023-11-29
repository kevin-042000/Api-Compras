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

    //Define la relación "belongsTo" con el modelo Category.
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define la relación "belongsTo" con el modelo Brand.
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //Define la relación "belongsToMany" con el modelo Purchase a través de la tabla purchase_products.
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_products')
            ->withPivot('price', 'quantity', 'subtotal')
            ->withTimestamps();
    }
}


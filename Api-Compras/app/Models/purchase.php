<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtotal', 'total'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_products')
            ->withPivot('price', 'quantity', 'subtotal')
            ->withTimestamps();
    }
}
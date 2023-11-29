<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id', 'product_id', 'price', 'quantity', 'subtotal'
    ];


    //Obtiene el producto asociado a la compra del modelo.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //Obtiene la compra asociada al modelo.
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}

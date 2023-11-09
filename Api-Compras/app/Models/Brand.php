<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description'
    ];

    public function products() // Una marca puede tener muchos productos asociados.
    {
        return $this->hasMany(Product::class);
    }
}
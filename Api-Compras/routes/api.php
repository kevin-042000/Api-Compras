<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

// Rutas para Categoría
Route::apiResource('categorias', CategoryController::class);
Route::get('categorias/{categoria}/productos', [CategoryController::class, 'productsByCategory']);

// Rutas para Marca
Route::apiResource('marcas', BrandController::class);
Route::get('marcas/{marca}/productos', [BrandController::class, 'productsByBrand']);

// Rutas para Producto
Route::apiResource('productos', ProductController::class);

// Rutas para Compra
Route::apiResource('compras', PurchaseController::class);

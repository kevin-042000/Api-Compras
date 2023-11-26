<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

//Rutas para Categoria
Route::apiResource('categorias', CategoryController::class);
Route::apiResource('marcas', BrandController::class);
Route::apiResource('productos', ProductController::class);
Route::apiResource('compras', PurchaseController::class);

Route::get('categorias/{categoria}/productos', [CategoryController::class,'productsByCategory']);
Route::get('marcas/{marca}/productos', [BrandController::class,'productsByBrand']);
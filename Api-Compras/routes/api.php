<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;

//Rutas para Categoria
Route::apiResource('categorias', CategoryController::class);
Route::apiResource('marcas', BrandController::class);
Route::apiResource('productos', ProductController::class);
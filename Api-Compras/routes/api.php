<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

//Rutas para Categoria
Route::apiResource('categorias', CategoryController::class);
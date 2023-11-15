<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {   //Retorna las Categorias existentes.

        try{
            $categories =  Category::all();
            return ApiResponse::success('Lista de Categorias', 200, $categories);

        } catch(Exception $e){
            return ApiResponse::error('Error al obtener la lista de categorias: '.$e->getMessage(), 500);
        }

       
    }
}

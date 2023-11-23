<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {   //Retorna las Categorias existentes.

        try{
            $categories =  Category::all();
            return ApiResponse::success('Lista de Categorias', 200, $categories);

        } catch(Exception $e){
            return ApiResponse::error('Error al obtener la lista de Categorias: '.$e->getMessage(), 500);
        }
       
    }

    public function store(StoreCategoryRequest $request)
    {
       try{
        $categoria = Category::create($request->all());
        return ApiResponse::success('Categoria creada exitosamente', 201, $categoria);

       } catch(ValidationException $e){
        return ApiResponse::error('Error de validacion: '.$e->getMessage(), 422);
       }
    }

    public function show($id)
    {
        try{
            $categoria = Category::findOrFail($id);
            return ApiResponse::success('Categoria obtinida exitosamente', 200, $categoria);

        } catch(ModelNotFoundException $e){
            return ApiResponse::error('Categoria no encontrada', 404);
        }

    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $categoria = Category::findOrFail($id); 
            $categoria->update($request->all());
            return ApiResponse::success('Categoria actualizada exitosamente', 200, $categoria);
    
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Categoria no encontrada', 404);
        } catch (Exception $e){
            return ApiResponse::error('Error al obtener la lista de Categorias: '.$e->getMessage(), 500);

        }
    }

    public function destroy($id)
    {
        try{
            $categoria = Category::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Categoria eliminada exitosamente', 200);
        } catch(ModelNotFoundException $e) {
            return ApiResponse::error('Categoria no encontrada', 404);

        }

    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {   //Retorna los Productos existentes.

        try{
            $productos =  Product::all();
            return ApiResponse::success('Lista de Productos', 200, $productos);

        } catch(Exception $e){
            return ApiResponse::error('Error al obtener la lista de Productos: '.$e->getMessage(), 500);
        }
       
    }

    public function store(StoreProductRequest $request)
    {
       try{
        $producto = Product::create($request->all());
        return ApiResponse::success('Producto creado exitosamente', 201, $producto);

       } catch(ValidationException $e){
        $errors = $e->validator-errors()->toArray(); //muesta todos los errores en formato array

        return ApiResponse::error('Error de validacion', 422);
       }
    }

    public function show($id)
    {
        try{
            $producto = Product::findOrFail($id);
            return ApiResponse::success('Producto obtinido exitosamente', 200, $producto);

        } catch(ModelNotFoundException $e){
            return ApiResponse::error('Producto no encontrado', 404);
        }

    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $producto = Product::findOrFail($id); 
            $producto->update($request->all());
            return ApiResponse::success('Producto actualizado exitosamente', 200, $producto);
    
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Producto no encontrado', 404);
        }  catch(ValidationException $e){
            $errors = $e->validator-errors()->toArray(); //muesta todos los errores en formato array
    
            return ApiResponse::error('Error de validacion', 422);
        }
    }

    public function destroy($id)
    {
        try{
            $producto = Product::findOrFail($id);
            $producto->delete();
            return ApiResponse::success('Producto eliminado exitosamente', 200);
        } catch(ModelNotFoundException $e) {
            return ApiResponse::error('Producto no encontrado', 404);

        }

    }

}

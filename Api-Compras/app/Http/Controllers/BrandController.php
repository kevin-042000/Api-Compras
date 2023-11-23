<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends Controller
{
    public function index()
    {   //Retorna las Marcas existentes.

        try{
            $marcas =  Brand::all();
            return ApiResponse::success('Lista de Marcas', 200, $marcas);

        } catch(Exception $e){
            return ApiResponse::error('Error al obtener la lista de Marcas: '.$e->getMessage(), 500);
        }
       
    }

    public function store(StoreBrandRequest $request)
    {
       try{
        $marcas = Brand::create($request->all());
        return ApiResponse::success('Marca creada exitosamente', 201, $marcas);

       } catch(ValidationException $e){
        return ApiResponse::error('Error de validacion: '.$e->getMessage(), 422);
       }
    }

    public function show($id)
    {
        try{
            $marcas = Brand::findOrFail($id);
            return ApiResponse::success('Marca obtinida exitosamente', 200, $marcas);

        } catch(ModelNotFoundException $e){
            return ApiResponse::error('Marca no encontrada', 404);
        }

    }

    public function update(UpdateBrandRequest $request, $id)
    {
        try {
            $marcas = Brand::findOrFail($id); 
            $marcas->update($request->all());
            return ApiResponse::success('Marca actualizada exitosamente', 200, $marcas);
    
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Marca no encontrada', 404);
        } catch (Exception $e){
            return ApiResponse::error('Error al obtener la lista de Marcas: '.$e->getMessage(), 500);

        }
    }

    public function destroy($id)
    {
        try{
            $marcas = Brand::findOrFail($id);
            $marcas->delete();
            return ApiResponse::success('Marca eliminada exitosamente', 200);
        } catch(ModelNotFoundException $e) {
            return ApiResponse::error('Marca no encontrada', 404);

        }

    }

}

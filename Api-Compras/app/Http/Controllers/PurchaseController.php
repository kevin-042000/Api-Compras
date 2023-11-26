<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Exception;
use QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            // Obtener la lista de productos de la solicitud
            $productos = $request->input('productos');
    
            // Validar si se proporcionaron productos
            if (empty($productos)) {
                return ApiResponse::error('No se proporcionaron productos', 400);
            }
    
            // Validar la estructura y los datos de la lista de productos
            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.product_id' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1'
            ]);
    
            // Si la validación falla, retornar un error con los detalles
            if ($validator->fails()) {
                return ApiResponse::error('Datos inválidos en la lista de productos', 400, $validator->errors()->all());
            }
    
            // Validar productos duplicados
            $productoIds = array_column($productos, 'product_id');
            if (count($productoIds) !== count(array_unique($productoIds))) {
                return ApiResponse::error('No se permiten productos duplicados para la compra', 400);
            }
    
            // Inicializar variables para el cálculo de totales y los items de la compra
            $totalPagar = 0;
            $compraItems = [];
    
            // Iterar sobre los productos para realizar cálculos y actualizaciones
            foreach ($productos as $producto) {
                // Obtener el producto de la base de datos
                $productoB = Producto::find($producto['product_id']);
    
                // Validar si el producto existe
                if (!$productoB) {
                    return ApiResponse::error('Producto no encontrado', 404);
                }
    
                // Validar la cantidad disponible de los productos
                if ($productoB->available_quantity < $producto['quantity']) {
                    return ApiResponse::error('El producto no tiene suficiente cantidad disponible', 404);
                }
    
                // Actualización de la cantidad disponible de cada producto
                $productoB->available_quantity -= $producto['quantity'];
                $productoB->save();
    
                // Cálculo de los importes
                $subtotal = $productoB->price * $producto['quantity'];
                $totalPagar += $subtotal;
    
                // Items de la compra
                $compraItems[] = [
                    'product_id' => $productoB->id,
                    'price' => $productoB->price,
                    'quantity' => $producto['quantity'],
                    'subtotal' => $subtotal
                ];
            }
    
            // Registro en la tabla compra
            $compra = Purchase::create([
                'subtotal' => $totalPagar,
                'total' => $totalPagar
            ]);
    
            // Asociar los productos a la compra con sus cantidades y subtotales
            $compra->products()->attach($compraItems);
    
            // Respuesta exitosa con los detalles de la compra
            return ApiResponse::success('Compra realizada exitosamente', 201, $compra);
    
        } catch (QueryException $e) {
            // Error de consulta en la base de datos
            return ApiResponse::error('Error en la consulta de base de datos', 500);
        } catch (Exception $e) {
            // Error inesperado
            return ApiResponse::error('Error inesperado', 500);
        }
    }
    

    public function show(string $id)
    {
        //
    }

    
    public function edit(string $id)
    {
        //
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        try {
            $productos = $request->input('productos');

            if (empty($productos)) {
                return ApiResponse::error('No se proporcionaron productos', 400);
            }

            $validator = validator($request->all(), [
                'productos' => 'required|array',
                'productos.*.product_id' => 'required|integer|exists:products,id',
                'productos.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $productoIds = array_column($productos, 'product_id');
            if (count($productoIds) !== count(array_unique($productoIds))) {
                return ApiResponse::error('No se permiten productos duplicados para la compra', 400);
            }

            $totalPagar = 0;
            $compraItems = [];

            foreach ($productos as $producto) {
                $productoB = Product::findOrFail($producto['product_id']);

                if ($productoB->quantity_available < $producto['quantity']) {
                    return ApiResponse::error('La cantidad solicitada del producto no está disponible', 400);
                }

                $productoB->decrement('quantity_available', $producto['quantity']);

                $subtotal = $productoB->price * $producto['quantity'];
                $totalPagar += $subtotal;

                $compraItems[] = [
                    'product_id' => $productoB->id,
                    'price' => $productoB->price,
                    'quantity' => $producto['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            $compra = Purchase::create([
                'subtotal' => $totalPagar,
                'total' => $totalPagar,
            ]);

            $compra->products()->attach($compraItems);

            return ApiResponse::success('Compra realizada exitosamente', 201, $compra);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->validator->errors()->all());
        } catch (QueryException $e) {
            return ApiResponse::error('Error en la consulta de base de datos', 500);
        } catch (\Exception $e) {
            return ApiResponse::error('Error inesperado', 500);
        }
    }
}

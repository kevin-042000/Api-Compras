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
            // Obtener la lista de productos de la solicitud
            $productos = $request->input('productos');

            // Verificar si se proporcionaron productos
            if (empty($productos)) {
                return ApiResponse::error('No se proporcionaron productos', 400);
            }

            // Validar la estructura y los datos de la lista de productos
            $validator = validator($request->all(), [
                'productos' => 'required|array',
                'productos.*.product_id' => 'required|integer|exists:products,id',
                'productos.*.quantity' => 'required|integer|min:1',
            ]);

            // Si la validación falla, lanzar una excepción de validación
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Validar productos duplicados
            $productoIds = array_column($productos, 'product_id');
            if (count($productoIds) !== count(array_unique($productoIds))) {
                return ApiResponse::error('No se permiten productos duplicados para la compra', 400);
            }

            $totalPagar = 0;
            $compraItems = [];

            // Mensaje de productos comprados
            $mensajeProductos = 'Comprado:';

            foreach ($productos as $producto) {
                // Obtener el producto de la base de datos
                $productoB = Product::findOrFail($producto['product_id']);

                // Validar la cantidad disponible del producto
                if ($productoB->quantity_available < $producto['quantity']) {
                    return ApiResponse::error('La cantidad solicitada del producto no está disponible', 400);
                }

                // Actualizar la cantidad disponible del producto
                $productoB->decrement('quantity_available', $producto['quantity']);

                // Calcular el subtotal del producto
                $subtotal = $productoB->price * $producto['quantity'];
                $totalPagar += $subtotal;

                // Detalles del producto en la compra
                $compraItems[] = [
                    'product_id' => $productoB->id,
                    'price' => $productoB->price,
                    'quantity' => $producto['quantity'],
                    'subtotal' => $subtotal,
                ];

                // Actualizar el mensaje de productos comprados
                $mensajeProductos .= " {$productoB->name} x{$producto['quantity']},";
            }

            // Crear una nueva instancia de compra
            $compra = Purchase::create([
                'subtotal' => $totalPagar,
                'total' => $totalPagar,
            ]);

            // Asociar los productos a la compra con sus cantidades y subtotales
            $compra->products()->attach($compraItems);

            // Mensaje de éxito con detalles de la compra
            $mensajeProductos = rtrim($mensajeProductos, ',');
            return ApiResponse::success("Compra realizada exitosamente. $mensajeProductos", 201, $compra);
        } catch (ValidationException $e) {
            // Manejar errores de validación
            return ApiResponse::error('Error de validación', 422, $e->validator->errors()->all());
        } catch (QueryException $e) {
            // Manejar errores de consulta en la base de datos
            return ApiResponse::error('Error en la consulta de base de datos', 500);
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            return ApiResponse::error('Error inesperado', 500);
        }
    }
}

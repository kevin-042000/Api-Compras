<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array', // Asegura que exista un campo llamado `products` en la solicitud y que sea un array.
            'products.*.product_id' => 'required|integer|exists:products,id', // Para cada elemento en `products`, valida la presencia de `product_id` como un número entero existente en la tabla `products`.
            'products.*.quantity' => 'required|integer|min:1', // Para cada elemento en `products`, valida la presencia de `quantity` como un número entero mayor o igual a 1.

        ];
    }
}

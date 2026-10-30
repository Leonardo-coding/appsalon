<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    // Segunda barrera de seguridad: solo administradores
    public function authorize(): bool
    {
        return (bool) $this->user()?->admin;
    }

    public function rules(): array
    {
        return [
            // En la edición se ignora el propio servicio al comprobar que el nombre sea único
            'nombre' => [
                'required', 'string', 'max:60',
                Rule::unique('servicios', 'nombre')->ignore($this->route('servicio')),
            ],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar :max caracteres.',
            'nombre.unique'   => 'Ya existe un servicio con ese nombre.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric'  => 'El precio debe ser un número.',
            'precio.min'      => 'El precio debe ser mayor que cero.',
            'precio.max'      => 'El precio no puede superar :max.',
            'precio.decimal'  => 'El precio admite máximo 2 decimales.',
        ];
    }
}

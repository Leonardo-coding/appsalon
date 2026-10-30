<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:60'],
            'apellido' => ['required', 'string', 'max:60'],
            'email'    => ['required', 'email', 'max:100', 'unique:usuarios,email'],
            'telefono' => ['nullable', 'regex:/^[0-9+\s-]{7,20}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'           => 'El campo :attribute es obligatorio.',
            'email.email'        => 'Escribe un correo electrónico válido.',
            'email.unique'       => 'Ese correo ya está registrado.',
            'max'                => 'El campo :attribute no puede superar :max caracteres.',
            'telefono.regex'     => 'El teléfono solo admite números, espacios, + y guiones (7 a 20 caracteres).',
            'password.min'       => 'La contraseña debe tener mínimo :min caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // Solo se usan los campos validados; "admin" nunca viene del formulario
        $user = User::create([
            ...$request->validated(),
            'admin'      => false,
            // En la entrega final la cuenta se confirmará por token enviado al correo
            'confirmado' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('exito', '¡Cuenta creada! Bienvenido/a a AppSalon.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credenciales = $request->only('email', 'password') + ['confirmado' => true];

        if (! Auth::attempt($credenciales)) {
            // Mensaje genérico: no revela si el correo existe
            return back()
                ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
                ->onlyInput('email');
        }

        // Evita fijación de sesión
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('exito', 'Sesión iniciada.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('exito', 'Sesión cerrada correctamente.');
    }
}
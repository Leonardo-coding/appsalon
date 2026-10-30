@extends('layouts.app')

@section('titulo', 'Inicio - AppSalon')

@section('contenido')
    <div class="p-5 bg-white rounded shadow-sm text-center">
        <h1>AppSalon</h1>
        <p class="lead">Sistema de gestión de citas para tu salón de belleza.</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta</a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar sesión</a>
        @endguest
    </div>
@endsection
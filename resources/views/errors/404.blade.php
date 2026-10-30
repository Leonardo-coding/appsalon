@extends('layouts.app')
@section('titulo', 'Página no encontrada')
@section('contenido')
    <div class="text-center py-5">
        <h1 class="display-4">404</h1>
        <p class="lead">La página que buscas no existe.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio</a>
    </div>
@endsection
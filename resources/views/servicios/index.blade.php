@extends('layouts.app')

@section('titulo', 'Servicios - AppSalon')

@section('contenido')
@php($esAdmin = auth()->check() && auth()->user()->admin)

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Nuestros servicios</h1>
    @if ($esAdmin)
        <a href="{{ route('servicios.create') }}" class="btn btn-primary">Nuevo servicio</a>
    @endif
</div>

@if ($servicios->isEmpty())
    <div class="alert alert-info">Todavía no hay servicios registrados.</div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($servicios as $servicio)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 card-title">{{ $servicio->nombre }}</h2>
                        <p class="card-text fs-4 text-success mb-0">${{ number_format($servicio->precio, 2) }}</p>
                    </div>

                    @if ($esAdmin)
                        <div class="card-footer bg-white d-flex gap-2">
                            <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form method="POST" action="{{ route('servicios.destroy', $servicio) }}"
                                  onsubmit="return confirm('¿Eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
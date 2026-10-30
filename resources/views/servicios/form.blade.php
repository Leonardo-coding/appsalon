@extends('layouts.app')

@section('titulo', isset($servicio) ? 'Editar servicio' : 'Nuevo servicio')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h4 mb-3">{{ isset($servicio) ? 'Editar servicio' : 'Nuevo servicio' }}</h2>

                <form method="POST" novalidate
                      action="{{ isset($servicio) ? route('servicios.update', $servicio) : route('servicios.store') }}">
                    @csrf
                    @if (isset($servicio))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre"
                               value="{{ old('nombre', $servicio->nombre ?? '') }}"
                               class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="text" inputmode="decimal" id="precio" name="precio"
                               value="{{ old('precio', $servicio->precio ?? '') }}"
                               class="form-control @error('precio') is-invalid @enderror">
                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Guardar</button>
                        <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
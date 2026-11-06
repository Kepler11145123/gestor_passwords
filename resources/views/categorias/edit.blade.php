@extends('layouts.app')

@section('titulo', 'Editar Bóveda')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Editar Bóveda: {{ $categoria->nombre }}</h1>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
              Volver al Listado
        </a>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre de la Bóveda / Categoría *</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción (Opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Bóveda</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

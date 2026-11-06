@extends('layouts.app')

@section('titulo', 'Crear Nueva Bóveda')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Crear Nueva Bóveda de Contraseñas</h1>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
              Volver al Listado
        </a>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre de la Bóveda / Categoría *</label>
                <input type="text" id="nombre" name="nombre" list="presets-boveda" value="{{ old('nombre') }}" placeholder="Elige una sugerencia o escribe un nombre propio" required>
                <datalist id="presets-boveda">
                    @foreach(['Trabajo', 'Personal', 'Finanzas', 'Redes sociales', 'Correo electrónico', 'Compras', 'Entretenimiento', 'Desarrollo'] as $preset)
                        <option value="{{ $preset }}">
                    @endforeach
                </datalist>
                <small class="text-muted">Puedes elegir una sugerencia o escribir un nombre personalizado. Después selecciona esta bóveda al crear tus credenciales.</small>
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción (Opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="4" placeholder="Breve descripción del propósito de esta bóveda">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Bóveda</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

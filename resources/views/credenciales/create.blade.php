@extends('layouts.app')

@section('titulo', 'Registrar Nueva Credencial')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Registrar Nueva Credencial</h1>
        <a href="{{ route('credenciales.index') }}" class="btn btn-secondary">
            ← Volver al Listado
        </a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <form action="{{ route('credenciales.store') }}" method="POST">
            @csrf

            <!-- Bóveda / Categoría Dinámica -->
            <div class="form-group">
                <label for="categoria_id">Bóveda / Categoría *</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="" disabled {{ old('categoria_id') ? '' : 'selected' }}>-- Selecciona una Bóveda --</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Identificador Personalizado Alfanumérico -->
            <div class="form-group">
                <label for="id_credencial">Identificador Personalizado (Clave Primaria Alfanumérica)</label>
                <input type="text" id="id_credencial" name="id_credencial" value="{{ old('id_credencial') }}" placeholder="Opcional: ej. CRD-TRABAJO-01 (Si se omite, se generará uno automático)">
                <small class="text-muted">Dejar vacío para autogenerar un código único seguro.</small>
                @error('id_credencial')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Título del Servicio -->
            <div class="form-group">
                <label for="titulo">Título / Servicio *</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ej: Correo Corporativo, GitHub, Banco..." required>
                @error('titulo')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Usuario o Correo -->
            <div class="form-group">
                <label for="usuario">Usuario / Correo Electrónico *</label>
                <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" placeholder="Ej: usuario@empresa.com o admin" required>
                @error('usuario')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contraseña a encriptar -->
            <div class="form-group">
                <label for="password">Contraseña Secreta *</label>
                <input type="password" id="password" name="password" placeholder="Ingresa la contraseña a almacenar" required>
                <small class="text-muted">Se cifrará inmediatamente en reposo con AES-256 antes de guardarse en la base de datos.</small>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- URL del Sitio -->
            <div class="form-group">
                <label for="url">URL o Enlace de Acceso (Opcional)</label>
                <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://ejemplo.com/login">
                @error('url')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notas Adicionales -->
            <div class="form-group">
                <label for="notas">Notas Seguras (Opcional)</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Preguntas de seguridad, códigos PIN u observaciones">{{ old('notas') }}</textarea>
                @error('notas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar y Cifrar Credencial</button>
                <a href="{{ route('credenciales.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

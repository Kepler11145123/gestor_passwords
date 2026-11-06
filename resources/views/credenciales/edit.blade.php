@extends('layouts.app')

@section('titulo', 'Editar Credencial: ' . $credencial->titulo)

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Editar Credencial: {{ $credencial->titulo }}</h1>
        <a href="{{ route('categorias.show', $credencial->categoria_id) }}" class="btn btn-secondary">
              Volver a la Bóveda
        </a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        @if($pinRecoveryPending)
            @include('credenciales._pin-recovery')
        @endif
        <form action="{{ route('credenciales.update', $credencial->id_credencial) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Identificador (Sólo Lectura en Edición) -->
            <div class="form-group">
                <label>Identificador de Credencial</label>
                <input type="text" value="{{ $credencial->id_credencial }}" disabled style="background-color: #f3f4f6; cursor: not-allowed;">
                <small class="text-muted">Las claves primarias alfanuméricas son inmutables.</small>
            </div>

            <!-- Bóveda / Categoría Dinámica -->
            <div class="form-group">
                <label for="categoria_id">Bóveda / Categoría *</label>
                <select id="categoria_id" name="categoria_id" required>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $credencial->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Título -->
            <div class="form-group">
                <label for="titulo">Título / Servicio *</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $credencial->titulo) }}" required>
                @error('titulo')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Usuario -->
            <div class="form-group">
                <label for="usuario">Usuario / Correo Electrónico *</label>
                <input type="text" id="usuario" name="usuario" value="{{ old('usuario', $credencial->usuario) }}" required>
                @error('usuario')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nueva Contraseña (Opcional) -->
            <div class="form-group">
                <label for="password">Nueva Contraseña (Opcional)</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña•••• (Dejar en blanco para conservar la actual)">
                <small class="text-muted">Solo escribe aquí si deseas cambiar la contraseña almacenada. El modelo la re-cifrará automáticamente.</small>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- URL -->
            <div class="form-group">
                <label for="url">URL o Enlace de Acceso (Opcional)</label>
                <input type="url" id="url" name="url" value="{{ old('url', $credencial->url) }}">
                @error('url')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notas -->
            <div class="form-group">
                <label for="notas">Notas Seguras (Opcional)</label>
                <textarea id="notas" name="notas" rows="3">{{ old('notas', $credencial->notas) }}</textarea>
                @error('notas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Credencial</button>
                <a href="{{ route('categorias.show', $credencial->categoria_id) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
@if(auth()->user()->vault_pin || ! auth()->user()->vault_pin_prompt_dismissed)
<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const keepAliveUrl = @json(route('credenciales.keep-alive', $credencial->id_credencial));
    const lockUrl = @json(route('credenciales.lock', $credencial->id_credencial));
    const lockedUrl = @json(route('credenciales.show', ['id_credencial' => $credencial->id_credencial, 'intent' => 'edit']));
    let timer;
    let lastTouch = 0;
    const lock = async () => {
        await fetch(lockUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' });
        window.location.href = lockedUrl;
    };
    const activity = () => {
        clearTimeout(timer);
        timer = setTimeout(lock, 20000);
        if (Date.now() - lastTouch > 10000) {
            lastTouch = Date.now();
            fetch(keepAliveUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' });
        }
    };
    ['pointerdown', 'keydown', 'touchstart', 'mousemove'].forEach(eventName => document.addEventListener(eventName, activity, { passive: true }));
    timer = setTimeout(lock, 20000);
})();
</script>
@endif
@endsection

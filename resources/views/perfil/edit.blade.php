@extends('layouts.app')

@section('titulo', 'Mi Perfil')

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">Mi Perfil</h1>
            <p class="text-muted">Actualiza tus datos personales, tu avatar y tu contraseña de acceso.</p>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <div class="card" style="margin-bottom: 0;">
            <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 1.25rem;">
                Datos Personales
            </h2>

            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <img src="{{ $usuario->avatarUrl() }}" alt="Avatar actual" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb;">
                <div>
                    <strong style="display: block; color: #111827;">{{ $usuario->name }}</strong>
                    <span class="text-muted">{{ $usuario->email }}</span>
                </div>
            </div>

            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Opcional">
                    @error('telefono')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar">Foto de Perfil</label>
                    <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp" style="padding: 0.5rem;">
                    <small class="text-muted">Formatos permitidos: JPG, PNG o WEBP. Tamaño máximo: 2 MB.</small>
                    @error('avatar')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>

        <div class="card" style="margin-bottom: 0;">
            <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 1.25rem;">
                Seguridad de la Cuenta
            </h2>

            <form action="{{ route('perfil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="password_actual">Contraseña Actual *</label>
                    <input type="password" id="password_actual" name="password_actual" required autocomplete="current-password">
                    @error('password_actual')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña *</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <small class="text-muted">Mínimo 8 caracteres.</small>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                    <a href="{{ route('hibp.index') }}" class="btn btn-secondary">Verificar Filtración</a>
                </div>
            </form>
        </div>
    </div>
@endsection

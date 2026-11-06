@extends('layouts.app')

@section('titulo', 'Mi Perfil')

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">Mi Perfil</h1>
            <p class="text-muted">Actualiza tus datos personales, tu avatar y tu contraseña de acceso.</p>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">  Volver al Dashboard</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <div class="card" style="margin-bottom: 0;">
            <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 1.25rem;">
                Datos Personales
            </h2>

            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="position:relative">
                    <button type="button" id="avatar-menu-trigger" aria-label="Opciones de foto de perfil" aria-expanded="false" style="padding:0;border:0;background:none;border-radius:50%;cursor:pointer">
                        <img id="avatarPreview" src="{{ $usuario->avatarUrl() }}" alt="Foto de perfil" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb;">
                    </button>
                    <div id="avatar-menu" hidden style="position:absolute;left:0;top:calc(100% + .5rem);z-index:20;min-width:210px;padding:.4rem;background:#fff;border:1px solid var(--border-color);border-radius:12px;box-shadow:0 12px 32px rgba(20,25,60,.16)">
                        <button type="button" id="avatar-change" class="btn btn-secondary" style="width:100%;justify-content:flex-start">Cambiar foto de perfil</button>
                        @if($tieneAvatar)
                            <form action="{{ route('perfil.avatar.remove') }}" method="POST" style="margin:.25rem 0 0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:flex-start;color:#dc2626">Eliminar foto de perfil</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div>
                    <strong style="display: block; color: #111827;">{{ $usuario->name }}</strong>
                    <span class="text-muted">{{ $usuario->email }}</span>
                </div>
            </div>

            <form id="avatar-upload-form" action="{{ route('perfil.avatar.update') }}" method="POST" enctype="multipart/form-data" hidden>
                @csrf
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp" required>
            </form>

            <form id="profile-form" action="{{ route('perfil.update') }}" method="POST">
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

                @error('avatar')<div class="form-error">{{ $message }}</div>@enderror

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
                    <small class="text-muted">Mínimo 12 caracteres, con mayúsculas, minúsculas, números y símbolos.</small>
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

        <div class="card" style="margin-bottom:0">
            <h2 style="font-size:1.15rem;font-weight:600;color:#374151;margin-bottom:.55rem">PIN de seguridad</h2>
            <p class="text-muted" style="margin-bottom:1rem">Estado: <strong>{{ $usuario->vault_pin ? 'Activado' : 'Desactivado' }}</strong>. Cada cambio requiere tu contraseña maestra.</p>
            <form action="{{ route('perfil.pin') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="enable">
                <div class="form-group">
                    <label for="vault_pin">{{ $usuario->vault_pin ? 'Nuevo PIN de 4 dígitos' : 'Activar PIN de 4 dígitos' }}</label>
                    <input type="password" id="vault_pin" name="vault_pin" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
                    @error('vault_pin')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="vault_pin_confirmation">Confirmar PIN</label>
                    <input type="password" id="vault_pin_confirmation" name="vault_pin_confirmation" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="master_password_pin_enable">Contraseña maestra</label>
                    <input type="password" id="master_password_pin_enable" name="master_password" autocomplete="current-password" required>
                    @error('master_password')<div class="form-error">{{ $message }}</div>@enderror
                    @error('master_password_pin')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ $usuario->vault_pin ? 'Cambiar PIN' : 'Activar PIN' }}</button>
            </form>

            @if($usuario->vault_pin)
                <form action="{{ route('perfil.pin') }}" method="POST" style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border-color)">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="disable">
                    <div class="form-group">
                        <label for="master_password_pin_disable">Contraseña maestra para desactivar</label>
                        <input type="password" id="master_password_pin_disable" name="master_password" autocomplete="current-password" required>
                        @error('master_password')<div class="form-error">{{ $message }}</div>@enderror
                        @error('master_password_pin')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-secondary">Desactivar y eliminar PIN</button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
const avatarInput = document.getElementById('avatar');
const avatarMenu = document.getElementById('avatar-menu');
const avatarTrigger = document.getElementById('avatar-menu-trigger');

avatarTrigger?.addEventListener('click', () => {
    avatarMenu.hidden = !avatarMenu.hidden;
    avatarTrigger.setAttribute('aria-expanded', String(!avatarMenu.hidden));
});
document.getElementById('avatar-change')?.addEventListener('click', () => {
    avatarMenu.hidden = true;
    avatarInput.click();
});
avatarInput?.addEventListener('change', (event) => {
    const file = event.target.files?.[0];
    if (file) {
        document.getElementById('avatarPreview').src = URL.createObjectURL(file);
        document.getElementById('avatar-upload-form').requestSubmit();
    }
});
document.addEventListener('click', (event) => {
    if (avatarMenu && !event.target.closest('#avatar-menu') && !event.target.closest('#avatar-menu-trigger')) {
        avatarMenu.hidden = true;
        avatarTrigger?.setAttribute('aria-expanded', 'false');
    }
});
</script>
@endsection

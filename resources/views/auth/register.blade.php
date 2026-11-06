@extends('layouts.auth')
@section('title', 'Crear cuenta')
@section('auth-content')
    <div class="verify-icon"><x-icon name="shield"/></div>
    <h1>Abre tu SecureVault</h1>
    <p class="auth-intro">Crea una cuenta. Te enviaremos un enlace para confirmar tu correo antes de entrar.</p>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="auth-form-group"><label for="name">Nombre</label><input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" autocomplete="name" required autofocus>@error('name')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <div class="auth-form-group"><label for="email">Correo electrónico</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nombre@correo.com" autocomplete="email" required>@error('email')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <div class="auth-form-group"><label for="telefono">Teléfono <span style="color:#959ab0;font-weight:500">(opcional)</span></label><input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Tu número">@error('telefono')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <div class="auth-grid">
            <div class="auth-form-group"><label for="password">Contraseña</label><input type="password" id="password" name="password" minlength="12" placeholder="Mínimo 12 caracteres" autocomplete="new-password" required>@error('password')<div class="auth-error">{{ $message }}</div>@enderror</div>
            <div class="auth-form-group"><label for="password_confirmation">Confirmar</label><input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la contraseña" autocomplete="new-password" required></div>
        </div>
        <p class="auth-intro" style="font-size:.8rem;margin:-.2rem 0 1.1rem">Incluye mayúsculas, minúsculas, números y símbolos.</p>
        <button type="submit" class="auth-btn">Crear cuenta</button>
    </form>
    <p class="auth-footer">¿Ya tienes cuenta?<a href="{{ route('login') }}" class="auth-link">Iniciar sesión</a></p>
@endsection

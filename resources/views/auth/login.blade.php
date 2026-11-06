@extends('layouts.auth')
@section('title', 'Iniciar sesión')
@section('auth-content')
    <div class="verify-icon"><x-icon name="key"/></div>
    <h1>Qué bueno verte</h1>
    <p class="auth-intro">Inicia sesión para volver a tu espacio seguro.</p>
    @if(session('success'))<div class="auth-note">{{ session('success') }}</div>@endif
    @if(session('status'))<div class="auth-note">{{ session('status') }}</div>@endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="auth-form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nombre@correo.com" autocomplete="email" required autofocus>
            @error('email')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="auth-form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" autocomplete="current-password" required>
            @error('password')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="auth-between">
            <label class="auth-check"><input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Mantener mi sesión</label>
            <a href="{{ route('password.request') }}" class="auth-link">¿Olvidaste tu contraseña?</a>
        </div>
        <button type="submit" class="auth-btn">Entrar a SecureVault</button>
    </form>
    <p class="auth-footer">¿Aún no tienes cuenta?<a href="{{ route('register') }}" class="auth-link">Crear una cuenta</a></p>
@endsection

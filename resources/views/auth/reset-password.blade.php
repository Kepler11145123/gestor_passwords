@extends('layouts.auth')
@section('title', 'Restablecer contraseña')
@section('auth-content')
    <div class="verify-icon"><x-icon name="lock"/></div>
    <h1>Elige una contraseña nueva</h1>
    <p class="auth-intro">Usa una contraseña segura que no hayas usado antes.</p>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="auth-form-group"><label for="email">Correo electrónico</label><input type="email" id="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required>@error('email')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <div class="auth-form-group"><label for="password">Nueva contraseña</label><input type="password" id="password" name="password" minlength="12" placeholder="Mínimo 12 caracteres" autocomplete="new-password" required>@error('password')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <div class="auth-form-group"><label for="password_confirmation">Confirmar contraseña</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required></div>
        <button type="submit" class="auth-btn">Guardar contraseña</button>
    </form>
@endsection

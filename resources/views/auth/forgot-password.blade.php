@extends('layouts.auth')
@section('title', 'Recuperar contraseña')
@section('auth-content')
    <div class="verify-icon"><x-icon name="mail"/></div>
    <h1>Recupera el acceso</h1>
    <p class="auth-intro">Indica el correo asociado a tu cuenta y te enviaremos un enlace para crear otra contraseña.</p>
    @if(session('success'))<div class="auth-note">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="auth-form-group"><label for="email">Correo electrónico</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nombre@correo.com" autocomplete="email" required autofocus>@error('email')<div class="auth-error">{{ $message }}</div>@enderror</div>
        <button type="submit" class="auth-btn">Enviar enlace de recuperación</button>
    </form>
    <p class="auth-footer"><a href="{{ route('login') }}" class="auth-link">Volver a iniciar sesión</a></p>
@endsection

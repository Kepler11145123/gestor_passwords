@extends('layouts.auth')
@section('title', 'Verifica tu correo')
@section('auth-content')
    <div class="verify-icon"><x-icon name="mail"/></div>
    <span class="auth-eyebrow" style="color:#5658d8">Un último paso</span>
    <h1>Confirma tu correo</h1>
    <p class="auth-intro">Enviamos un código de seis dígitos a tu correo. Introdúcelo aquí para activar el acceso a tu bóveda.</p>
    <div class="auth-email-pill"><x-icon name="mail"/> {{ $email }}</div>
    @if(session('status'))<div class="auth-note">{{ session('status') }}</div>@endif
    @if(session('success'))<div class="auth-note">{{ session('success') }}</div>@endif
    @error('email')<div class="auth-note error">{{ $message }}</div>@enderror
    <form method="POST" action="{{ route('verification.code') }}" style="margin-top:1.4rem">
        @csrf
        <div class="auth-form-group">
            <label for="code">Código de verificación</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="000000" required autofocus>
            @error('code')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <button class="auth-btn" type="submit">Verificar correo</button>
    </form>
    <form method="POST" action="{{ route('verification.send') }}" style="margin-top:.75rem">
        @csrf
        <button class="auth-btn auth-btn-secondary" type="submit">Enviar un código nuevo</button>
    </form>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:.75rem">
        @csrf
        <button class="auth-btn auth-btn-secondary" type="submit"><x-icon name="logout"/> Cerrar sesión</button>
    </form>
    <p class="auth-footer">El código vence en 15 minutos. Revisa correo no deseado si no lo encuentras.</p>
@endsection

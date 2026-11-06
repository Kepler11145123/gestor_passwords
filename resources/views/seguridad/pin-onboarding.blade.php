@extends('layouts.app')

@section('titulo', 'Configura tu PIN')

@section('contenido')
<div class="card" style="max-width:620px;margin:3rem auto">
    <span class="auth-eyebrow" style="color:var(--primary)">Seguridad opcional</span>
    <h1 class="page-title" style="margin:.4rem 0 .75rem">¿Deseas activar un PIN de 4 dígitos?</h1>
    <p class="text-muted" style="margin-bottom:1.5rem">El PIN añade una confirmación rápida antes de revelar o editar credenciales. Puedes configurarlo ahora o hacerlo después desde tu perfil.</p>

    <form action="{{ route('security.pin.onboarding.store') }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="configure">
        <div class="form-group">
            <label for="vault_pin">PIN nuevo</label>
            <input type="password" id="vault_pin" name="vault_pin" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
            @error('vault_pin')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="vault_pin_confirmation">Confirma el PIN</label>
            <input type="password" id="vault_pin_confirmation" name="vault_pin_confirmation" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
        </div>
        <button type="submit" class="btn btn-primary">Activar PIN</button>
    </form>

    <form action="{{ route('security.pin.onboarding.store') }}" method="POST" style="margin-top:.8rem">
        @csrf
        <input type="hidden" name="action" value="skip">
        <button type="submit" class="btn btn-secondary">Omitir / Configurar después</button>
    </form>
</div>
@endsection

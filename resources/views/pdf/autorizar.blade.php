@extends('layouts.app')
@section('titulo', 'Autorizar informe PDF')
@section('contenido')
<div class="card" style="max-width:560px;margin:2rem auto">
    <h1 class="page-title">Autorizar informe PDF</h1>
    <p class="text-muted" style="margin:1rem 0">Confirma tu contraseña de acceso para preparar la descarga.</p>
    <form method="POST" action="{{ route('informe.pdf') }}">
        @csrf
        <div class="form-group"><label for="password">Contraseña de acceso</label><input type="password" id="password" name="password" required autocomplete="current-password">@error('password')<div class="form-error">{{ $message }}</div>@enderror</div>
        <button class="btn btn-primary" type="submit">Continuar</button>
        <a class="btn btn-secondary" href="{{ route('dashboard.index') }}">Cancelar</a>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('titulo', 'Descargar informe PDF')
@section('contenido')
<div class="card" style="max-width:560px;margin:2rem auto">
    <h1 class="page-title">Informe de seguridad listo</h1>
    <p class="text-muted" style="margin:1rem 0">El informe contiene estadísticas y datos de tus credenciales, sin incluir contraseñas ni notas.</p>
    <a class="btn btn-primary" href="{{ route('informe.pdf', ['download' => 1]) }}">Descargar PDF</a>
    <a class="btn btn-secondary" href="{{ route('dashboard.index') }}">Cancelar</a>
</div>
@endsection

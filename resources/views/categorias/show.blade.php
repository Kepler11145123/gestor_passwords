@extends('layouts.app')

@section('titulo', 'Detalle de Bóveda: ' . $categoria->nombre)

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $categoria->nombre }}</h1>
            <p class="text-muted">{{ $categoria->descripcion ?? 'Sin descripción adicional' }}</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('credenciales.create') }}" class="btn btn-primary">+ Nueva Credencial</a>
            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-secondary">Editar Bóveda</a>
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="card">
        <h2 style="font-size: 1.1rem; margin-bottom: 1rem; color: #374151;">
            Credenciales Asociadas a esta Bóveda
        </h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Título / Servicio</th>
                        <th>Usuario / Email</th>
                        <th>URL</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoria->credenciales as $credencial)
                        <tr>
                            <td><code>{{ $credencial->id_credencial }}</code></td>
                            <td><strong>{{ $credencial->titulo }}</strong></td>
                            <td>{{ $credencial->usuario }}</td>
                            <td>
                                @if($credencial->url)
                                    <a href="{{ $credencial->url }}" target="_blank" rel="noopener noreferrer" style="color: var(--primary);">
                                        Visitar enlace ↗
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('credenciales.show', $credencial->id_credencial) }}" class="btn btn-secondary btn-sm">Ver</a>
                                <a href="{{ route('credenciales.edit', $credencial->id_credencial) }}" class="btn btn-secondary btn-sm">Editar</a>
                                <form action="{{ route('credenciales.destroy', $credencial->id_credencial) }}" method="POST" class="form-eliminar" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;" class="text-muted">
                                No existen contraseñas guardadas en esta bóveda todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

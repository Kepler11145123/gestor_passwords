@extends('layouts.app')

@section('titulo', 'Bóvedas y Categorías')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Bóvedas de Seguridad (Categorías)</h1>
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            + Nueva Bóveda
        </a>
    </div>

    <!-- Gráfico Estadístico Dinámico (Chart.js) -->
    <div class="card">
        <h2 style="font-size: 1.1rem; margin-bottom: 1rem; color: #374151;">
            Distribución de Credenciales por Bóveda
        </h2>
        <div style="max-height: 280px; position: relative;">
            <canvas id="graficoCategorias"></canvas>
        </div>
    </div>

    <!-- Tabla de Bóvedas -->
    <div class="card">
        <div class="table-responsive">
            <table id="tablaCategorias">
                <thead>
                    <tr>
                        <th>Nombre de la Bóveda</th>
                        <th>Descripción</th>
                        <th>Total Credenciales</th>
                        <th>Fecha Creación</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>
                                <a href="{{ route('categorias.show', $categoria->id) }}" style="font-weight:700; color:inherit; text-decoration:underline; text-underline-offset:3px">
                                    {{ $categoria->nombre }}
                                </a>
                            </td>
                            <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                            <td>
                                <span class="badge">{{ $categoria->credenciales_count }} contraseñas</span>
                            </td>
                            <td>{{ $categoria->created_at ? $categoria->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-secondary btn-sm">Ver</a>
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-secondary btn-sm">Editar</a>
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="form-eliminar" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;" class="text-muted">
                                No hay bóvedas registradas actualmente. Presiona "+ Nueva Bóveda" para comenzar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cero datos quemados: Los arrays de etiquetas y valores provienen directamente del controlador
        const etiquetas = {!! json_encode($estadisticas->pluck('nombre')) !!};
        const valores = {!! json_encode($estadisticas->pluck('total')) !!};

        new DataTable('#tablaCategorias', {
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros disponibles',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                zeroRecords: 'No se encontraron bóvedas coincidentes',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { targets: -1, orderable: false, searchable: false }
            ]
        });

        const ctx = document.getElementById('graficoCategorias').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Cantidad de Credenciales Almacenadas',
                    data: valores,
                    backgroundColor: 'rgba(37, 99, 235, 0.65)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1.5,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

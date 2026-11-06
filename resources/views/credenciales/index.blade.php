@extends('layouts.app')

@section('titulo', 'Listado de Credenciales')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Bóveda de Credenciales y Contraseñas</h1>
        <a href="{{ route('credenciales.create') }}" class="btn btn-primary">
            + Nueva Credencial
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table id="tablaCredenciales">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Bóveda (Categoría)</th>
                        <th>Título / Servicio</th>
                        <th>Fecha de Registro</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($credenciales as $credencial)
                        <tr>
                            <td><code>{{ $credencial->id_credencial }}</code></td>
                            <td>
                                <span class="badge">{{ $credencial->categoria->nombre ?? 'Sin Bóveda' }}</span>
                            </td>
                            <td><strong>{{ $credencial->titulo }}</strong></td>
                            <td>{{ $credencial->created_at ? $credencial->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('credenciales.show', $credencial->id_credencial) }}" class="btn btn-secondary btn-sm">Ver</a>
                                <a href="{{ route('credenciales.edit', $credencial->id_credencial) }}" class="btn btn-secondary btn-sm">Editar</a>
                                
                                <!-- Formulario de eliminación con confirmación SweetAlert2 -->
                                <form action="{{ route('credenciales.destroy', $credencial->id_credencial) }}" 
                                      method="POST" 
                                      class="form-eliminar-credencial" 
                                      data-titulo="{{ $credencial->titulo }}"
                                      style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;" class="text-muted">
                                No hay credenciales registradas aún. Haz clic en "+ Nueva Credencial" para proteger tu primera contraseña.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<!-- Inclusión de SweetAlert2 en la vista -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        new DataTable('#tablaCredenciales', {
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Sin registros disponibles',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                zeroRecords: 'No se encontraron credenciales coincidentes',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            },
            pageLength: 10,
            order: [[5, 'desc']],
            columnDefs: [
                { targets: -1, orderable: false, searchable: false }
            ]
        });

        // 1. Alerta de éxito al registrar una nueva contraseña (o actualizar)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Aceptar',
                timer: 4000,
                timerProgressBar: true
            });
        @endif

        // 2. Alerta de confirmación que detiene el envío del formulario destroy
        const formulariosEliminar = document.querySelectorAll('.form-eliminar-credencial');

        formulariosEliminar.forEach(formulario => {
            formulario.addEventListener('submit', function (evento) {
                // Detiene el envío inmediato del formulario
                evento.preventDefault();

                const tituloServicio = this.getAttribute('data-titulo') || 'esta credencial';

                Swal.fire({
                    title: '¿Eliminar credencial?',
                    text: `¿Estás seguro de que deseas eliminar permanentemente la contraseña de "${tituloServicio}"? Esta acción no se puede revertir.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((resultado) => {
                    // Si el usuario confirma, procedemos con el envío del formulario
                    if (resultado.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

    });
</script>
@endsection

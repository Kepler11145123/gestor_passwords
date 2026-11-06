@extends('layouts.app')

@section('titulo', 'Dashboard Principal | Panel de Control')

@section('contenido')
    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('perfil.edit') }}" aria-label="Cambiar foto de perfil"><img src="{{ auth()->user()->avatarUrl() }}" alt="Foto de perfil" style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;"></a>
            <div>
                <h1 class="page-title">Bienvenido, {{ auth()->user()->name }}</h1>
                <p class="text-muted">Resumen estadístico en tiempo real del gestor de contraseñas y bóvedas.</p>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('credenciales.create') }}" class="btn btn-primary">Nueva Credencial</a>
            <a href="{{ route('categorias.create') }}" class="btn btn-secondary">Nueva Bóveda</a>
        </div>
    </div>

    <!-- Tarjetas de Métricas Estadísticas (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
        <div class="card" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0;">
            <div>
                <span class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Total de Bóvedas</span>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #1e3a8a;">{{ $totalCategorias }}</h3>
            </div>
        </div>

        <div class="card" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0;">
            <div>
                <span class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">Credenciales guardadas</span>
                <h3 style="font-size: 1.8rem; font-weight: 700; color: #14532d;">{{ $totalCredenciales }}</h3>
            </div>
        </div>

    </div>

    <!-- Sección de Gráficos con Chart.js -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        
        <!-- Histograma Principal de Credenciales por Bóveda -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151;">
                    Credenciales Almacenadas por Bóveda
                </h2>
                <span class="badge">Datos en Tiempo Real</span>
            </div>

            <!-- Contenedor del elemento Canvas requerido para Chart.js -->
            <div style="height: 320px; position: relative;">
                <canvas id="credencialesPorCategoriaChart"></canvas>
            </div>
        </div>

        <!-- Distribución Porcentual en Gráfico Circular -->
        <div class="card" style="margin-bottom: 0;">
            <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">
                Proporción por Bóveda
            </h2>
            <div style="height: 320px; position: relative;">
                <canvas id="doughnutCategoriasChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Acceso a credenciales organizado por bóveda -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.15rem; font-weight: 600; color: #374151;">
                Tus bóvedas
            </h2>
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-sm">Ver bóvedas</a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem">
            @forelse($bovedas as $boveda)
                <a href="{{ route('categorias.show', $boveda->id) }}" class="card" style="margin:0; color:inherit; text-decoration:none">
                    <strong>{{ $boveda->nombre }}</strong>
                    <div class="text-muted" style="margin-top:.45rem">{{ $boveda->credenciales_count }} credenciales</div>
                </a>
            @empty
                <p class="text-muted">Aún no tienes bóvedas. Crea una para comenzar a organizar tus credenciales.</p>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // CERO DATOS QUEMADOS:
        // Variables transmitidas directamente desde el controlador tras la consulta del modelo
        const labelsDinamicos = {!! json_encode($chartLabels) !!};
        const datosDinamicos  = {!! json_encode($chartData) !!};

        // Paleta de colores armónica para la visualización
        const paletaColores = [
            'rgba(37, 99, 235, 0.75)',   // Azul
            'rgba(16, 185, 129, 0.75)',  // Esmeralda
            'rgba(245, 158, 11, 0.75)',  // Ámbar
            'rgba(139, 92, 246, 0.75)',  // Violeta
            'rgba(236, 72, 153, 0.75)',  // Rosa
            'rgba(14, 165, 233, 0.75)',  // Cyan
            'rgba(249, 115, 22, 0.75)'   // Naranja
        ];

        const bordesColores = [
            'rgb(37, 99, 235)',
            'rgb(16, 185, 129)',
            'rgb(245, 158, 11)',
            'rgb(139, 92, 246)',
            'rgb(236, 72, 153)',
            'rgb(14, 165, 233)',
            'rgb(249, 115, 22)'
        ];

        // 1. Gráfico de Barras: Credenciales por Bóveda
        const ctxBarra = document.getElementById('credencialesPorCategoriaChart').getContext('2d');
        new Chart(ctxBarra, {
            type: 'bar',
            data: {
                labels: labelsDinamicos,
                datasets: [{
                    label: 'Número de Credenciales',
                    data: datosDinamicos,
                    backgroundColor: paletaColores,
                    borderColor: bordesColores,
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} contraseñas registradas`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Gráfico Donut Complementario: Proporción por Bóveda
        const ctxDonut = document.getElementById('doughnutCategoriasChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: labelsDinamicos,
                datasets: [{
                    data: datosDinamicos,
                    backgroundColor: paletaColores,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection

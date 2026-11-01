@extends('layouts.app')

@section('titulo', 'Detalle de Credencial: ' . $credencial->titulo)

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $credencial->titulo }}</h1>
            <p class="text-muted">Bóveda: <strong>{{ $credencial->categoria->nombre ?? 'Sin Bóveda' }}</strong></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('credenciales.edit', $credencial->id_credencial) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('credenciales.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="card" style="max-width: 750px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label>Identificador Único</label>
                <p><code>{{ $credencial->id_credencial }}</code></p>
            </div>
            <div>
                <label>Bóveda Asociada</label>
                <p><span class="badge">{{ $credencial->categoria->nombre ?? 'N/A' }}</span></p>
            </div>
            <div>
                <label>Usuario / Correo Electrónico</label>
                <p><strong>{{ $credencial->usuario }}</strong></p>
            </div>
            <div>
                <label>Enlace del Sitio / App</label>
                <p>
                    @if($credencial->url)
                        <a href="{{ $credencial->url }}" target="_blank" rel="noopener noreferrer" style="color: var(--primary);">
                            {{ $credencial->url }} ↗
                        </a>
                    @else
                        <span class="text-muted">No especificado</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Sección de Contraseña con Copiado y Desocultado Seguro -->
        <div class="card" style="background-color: #f9fafb; border: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
            <label style="margin-bottom: 0.5rem;">Contraseña Desencriptada</label>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="password" id="inputPassword" value="{{ $passwordPlana }}" readonly style="font-family: monospace; font-size: 1.05rem; letter-spacing: 0.05em; background-color: #ffffff;">
                <button type="button" id="btnToggle" class="btn btn-secondary" onclick="togglePasswordVisibility()">
                    👁️ Ver
                </button>
                <button type="button" class="btn btn-primary" onclick="copiarAlPortapapeles()">
                    📋 Copiar
                </button>
            </div>
            <small class="text-muted" style="margin-top: 0.4rem; display: block;">
                🔒 Esta clave se almacena cifrada en reposo mediante AES-256 (Illuminate\Support\Facades\Crypt).
            </small>
        </div>

        @if($credencial->notas)
            <div style="margin-bottom: 1.5rem;">
                <label>Notas Seguras</label>
                <div style="background-color: #f8fafc; padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border-color); white-space: pre-line;">
                    {{ $credencial->notas }}
                </div>
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <span class="text-muted">Registrado: {{ $credencial->created_at ? $credencial->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
            
            <form action="{{ route('credenciales.destroy', $credencial->id_credencial) }}" method="POST" class="form-eliminar">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Eliminar Credencial</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('inputPassword');
        const btn = document.getElementById('btnToggle');
        
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🔒 Ocultar';
        } else {
            input.type = 'password';
            btn.textContent = '👁️ Ver';
        }
    }

    function copiarAlPortapapeles() {
        const input = document.getElementById('inputPassword');
        navigator.clipboard.writeText(input.value).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '¡Contraseña copiada al portapapeles!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo copiar al portapapeles de manera automática.'
            });
        });
    }
</script>
@endsection

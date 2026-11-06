@extends('layouts.app')

@section('titulo', $credentialUnlocked ? 'Detalle de Credencial: ' . $credencial->titulo : 'Credencial protegida')

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $credentialUnlocked ? $credencial->titulo : 'Credencial protegida' }}</h1>
            @if($credentialUnlocked)
            <p class="text-muted">Bóveda: <strong>{{ $credencial->categoria->nombre ?? 'Sin Bóveda' }}</strong></p>
            @endif
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('credenciales.edit', $credencial->id_credencial) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('categorias.show', $credencial->categoria_id) }}" class="btn btn-secondary">Volver a la bóveda</a>
        </div>
    </div>

    <div class="card" style="max-width: 750px; margin: 0 auto;">
        @if($pinRecoveryPending)
            @include('credenciales._pin-recovery')
        @endif
        @if(!$credentialUnlocked)
        @if($pinRecoveryPending)
            <p class="text-muted">La vista sensible se cerró. Elige un PIN nuevo o continúa sin PIN.</p>
        @elseif(auth()->user()->vault_pin)
        <form action="{{ route('credenciales.reveal', $credencial->id_credencial) }}" method="POST">
            @csrf
            <input type="hidden" name="intent" value="{{ $intent }}">
            <div class="form-group">
                <label for="pin">PIN de 4 dígitos</label>
                <input type="password" id="pin" name="pin" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="off" required autofocus>
                @error('pin') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
        <button type="button" id="show-master-recovery" class="btn btn-secondary" style="margin-top:.75rem">¿Olvidaste tu PIN?</button>
        <form id="master-recovery-form" action="{{ route('credenciales.reveal', $credencial->id_credencial) }}" method="POST" style="margin-top:1rem" @unless($errors->has('master_password')) hidden @endunless>
            @csrf
            <input type="hidden" name="intent" value="{{ $intent }}">
            <div class="form-group">
                <label for="master_password">Confirma tu contraseña maestra para recuperar el acceso</label>
                <input type="password" id="master_password" name="master_password" autocomplete="current-password" required>
                @error('master_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Verificar y recuperar</button>
        </form>
        @else
            <div style="max-width:560px">
                <h2 style="font-size:1.1rem;margin-bottom:.5rem">Aún no tienes un PIN configurado</h2>
                <p class="text-muted" style="margin-bottom:1rem">Puedes crear un PIN de cuatro dígitos para proteger esta acción o continuar sin PIN. Configurarlo requiere tu contraseña maestra.</p>
                <form action="{{ route('credenciales.reveal', $credencial->id_credencial) }}" method="POST">
                    @csrf
                    <input type="hidden" name="intent" value="{{ $intent }}">
                    <div class="form-group">
                        <label for="master_password">Contraseña maestra</label>
                        <input type="password" id="master_password" name="master_password" autocomplete="current-password" required>
                        @error('master_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="new_pin">PIN de cuatro dígitos</label>
                        <input type="password" id="new_pin" name="new_pin" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
                        @error('new_pin')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="new_pin_confirmation">Confirmar PIN</label>
                        <input type="password" id="new_pin_confirmation" name="new_pin_confirmation" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Configurar PIN y continuar</button>
                </form>
                <form action="{{ route('credenciales.reveal', $credencial->id_credencial) }}" method="POST" style="margin-top:.8rem">
                    @csrf
                    <input type="hidden" name="intent" value="{{ $intent }}">
                    <input type="hidden" name="skip_pin" value="1">
                    <button type="submit" class="btn btn-secondary">Continuar sin PIN</button>
                </form>
            </div>
        @endif
        @else
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
                            {{ $credencial->url }}
                        </a>
                    @else
                        <span class="text-muted">No especificado</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- La contraseña se mantiene oculta hasta que se solicita. -->
        <div class="card" style="background-color: #f9fafb; border: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
            <label style="margin-bottom: 0.5rem;">Contraseña</label>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="password" id="inputPassword" value="{{ $passwordPlana }}" readonly style="font-family: monospace; font-size: 1.05rem; letter-spacing: 0.05em; background-color: #ffffff;">
                <button type="button" id="btnToggle" class="btn btn-secondary" onclick="togglePasswordVisibility()">
                    Ver
                </button>
                <button type="button" class="btn btn-primary" onclick="copiarAlPortapapeles()">
                    Copiar
                </button>
            </div>
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
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.getElementById('show-master-recovery')?.addEventListener('click', () => {
    const form = document.getElementById('master-recovery-form');
    form.hidden = !form.hidden;
    if (!form.hidden) document.getElementById('master_password').focus();
});

    function togglePasswordVisibility() {
        const input = document.getElementById('inputPassword');
        const btn = document.getElementById('btnToggle');
        
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = 'Ocultar';
        } else {
            input.type = 'password';
            btn.textContent = 'Ver';
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
@if($credentialUnlocked && (auth()->user()->vault_pin || ! auth()->user()->vault_pin_prompt_dismissed))
<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const keepAliveUrl = @json(route('credenciales.keep-alive', $credencial->id_credencial));
    const lockUrl = @json(route('credenciales.lock', $credencial->id_credencial));
    let timer;
    let lastTouch = 0;
    const lock = async () => {
        await fetch(lockUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' });
        window.location.reload();
    };
    const activity = () => {
        clearTimeout(timer);
        timer = setTimeout(lock, 20000);
        if (Date.now() - lastTouch > 10000) {
            lastTouch = Date.now();
            fetch(keepAliveUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' });
        }
    };
    ['pointerdown', 'keydown', 'touchstart', 'mousemove'].forEach(eventName => document.addEventListener(eventName, activity, { passive: true }));
    timer = setTimeout(lock, 20000);
})();
</script>
@endif
@endsection

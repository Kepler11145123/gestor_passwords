@extends('layouts.app')

@section('titulo', 'Verificador de Contraseñas Filtradas')

@section('contenido')
    <div class="page-header">
        <div>
            <h1 class="page-title">Verificar Filtración de Contraseña</h1>
            <p class="text-muted">Comprobación anónima mediante la API pública de HaveIBeenPwned (modelo k-Anonymity).</p>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">  Volver al Dashboard</a>
    </div>

    <div class="card" style="max-width: 650px; margin: 0 auto;">
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius); padding: 1rem; margin-bottom: 1.5rem;">
            <strong style="display: block; color: #1e40af; margin-bottom: 0.35rem;"> Tu contraseña permanece privada</strong>
            <span class="text-muted">
                La contraseña se verifica frente al servicio HaveIBeenPwned.
            </span>
        </div>

        <form id="hibpForm">
            @csrf
            <div class="form-group">
                <label for="password">Contraseña a Verificar *</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="password" id="password" name="password" placeholder="Escribe la contraseña a comprobar" required autocomplete="off">
                    <button type="button" id="btnToggle" class="btn btn-secondary" onclick="togglePassword()"></button>
                </div>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" id="btnVerificar" class="btn btn-primary">Verificar Ahora</button>
            </div>
        </form>

        <div id="resultado" style="display: none; margin-top: 1.5rem;"></div>
    </div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = document.getElementById('btnToggle');

        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '';
        } else {
            input.type = 'password';
            btn.textContent = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('hibpForm');
        const btn = document.getElementById('btnVerificar');
        const resultado = document.getElementById('resultado');

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const password = document.getElementById('password').value;

            btn.disabled = true;
            btn.textContent = 'Consultando...';
            resultado.style.display = 'none';

            try {
                const response = await fetch('{{ route('hibp.verificar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();

                if (!response.ok || data.status === 'error') {
                    throw new Error(data.mensaje || 'No se pudo verificar la contraseña.');
                }

                resultado.style.display = 'block';
                resultado.innerHTML = data.comprometida
                    ? `<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:1.25rem;">
                           <strong style="color:#991b1b;font-size:1.05rem;"> Contraseña comprometida</strong>
                           <p style="margin-top:0.5rem;color:#7f1d1d;">Esta contraseña apareció en <strong>${data.veces.toLocaleString('es')}</strong> filtraciones conocidas. Cámbiala de inmediato.</p>
                       </div>`
                    : `<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:1.25rem;">
                           <strong style="color:#166534;font-size:1.05rem;"> Sin filtraciones conocidas</strong>
                           <p style="margin-top:0.5rem;color:#14532d;">Esta contraseña no fue encontrada en las filtraciones registradas por HaveIBeenPwned.</p>
                       </div>`;

                Swal.fire({
                    icon: data.comprometida ? 'warning' : 'success',
                    title: data.comprometida ? 'Contraseña filtrada' : 'Contraseña segura',
                    text: data.mensaje,
                    confirmButtonColor: data.comprometida ? '#dc2626' : '#16a34a'
                });
            } catch (error) {
                resultado.style.display = 'block';
                resultado.innerHTML = `<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:1.25rem;color:#991b1b;">${error.message}</div>`;

                Swal.fire({
                    icon: 'error',
                    title: 'Error de consulta',
                    text: error.message,
                    confirmButtonColor: '#2563eb'
                });
            } finally {
                btn.disabled = false;
                btn.textContent = 'Verificar Ahora';
            }
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('titulo', 'Registrar Nueva Credencial')

@section('contenido')
    <div class="page-header">
        <h1 class="page-title">Registrar Nueva Credencial</h1>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
              Volver a las Bóvedas
        </a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <form action="{{ route('credenciales.store') }}" method="POST">
            @csrf

            <!-- Una credencial pertenece a una bóveda existente -->
            <div class="form-group">
                <label for="categoria_id">Bóveda / Categoría *</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">-- Selecciona una bóveda --</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ (string) $categoriaSeleccionada === (string) $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                @if($categorias->isEmpty())
                    <small class="text-muted">Primero crea una bóveda para guardar aquí esta credencial.</small>
                @else
                    <small class="text-muted">La credencial se guardará dentro de la bóveda seleccionada.</small>
                @endif
                <div style="margin-top:.75rem">
                    <button type="button" id="toggle-new-vault" class="btn btn-secondary">+ Agregar una nueva bóveda</button>
                </div>
                <div id="new-vault-panel" class="card" hidden style="margin-top:1rem; margin-bottom:0; padding:1rem">
                    <h2 style="font-size:1rem; margin-bottom:.9rem">Crear bóveda</h2>
                    <div class="form-group">
                        <label for="quick-vault-name">Nombre *</label>
                        <input type="text" id="quick-vault-name" list="quick-vault-presets" maxlength="100" placeholder="Elige un preset o escribe un nombre">
                        <datalist id="quick-vault-presets">
                            @foreach(['Trabajo', 'Personal', 'Finanzas', 'Redes sociales', 'Correo electrónico', 'Compras', 'Entretenimiento', 'Desarrollo'] as $preset)
                                <option value="{{ $preset }}">
                            @endforeach
                        </datalist>
                        <div id="quick-vault-name-error" class="form-error" hidden></div>
                    </div>
                    <div class="form-group">
                        <label for="quick-vault-description">Descripción (opcional)</label>
                        <textarea id="quick-vault-description" rows="2" maxlength="500" placeholder="Para qué usarás esta bóveda"></textarea>
                        <div id="quick-vault-description-error" class="form-error" hidden></div>
                    </div>
                    <div style="display:flex; gap:.6rem; align-items:center">
                        <button type="button" id="save-quick-vault" class="btn btn-primary">Crear y seleccionar bóveda</button>
                        <button type="button" id="cancel-quick-vault" class="btn btn-secondary">Cancelar</button>
                        <span id="quick-vault-status" class="text-muted" role="status"></span>
                    </div>
                </div>
            </div>

            <!-- Identificador Personalizado Alfanumérico -->
            <div class="form-group">
                <label for="id_credencial">Identificador Personalizado (Clave Primaria Alfanumérica)</label>
                <input type="text" id="id_credencial" name="id_credencial" value="{{ old('id_credencial') }}" placeholder="Opcional: ej. CRD-TRABAJO-01 (Si se omite, se generará uno automático)">
                <small class="text-muted">Dejar vacío para autogenerar un código único seguro.</small>
                @error('id_credencial')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Título del Servicio -->
            <div class="form-group">
                <label for="titulo">Título / Servicio *</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ej: Correo Corporativo, GitHub, Banco..." required>
                @error('titulo')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Usuario o Correo -->
            <div class="form-group">
                <label for="usuario">Usuario / Correo Electrónico *</label>
                <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" placeholder="Ej: usuario@empresa.com o admin" required>
                @error('usuario')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contraseña a encriptar -->
            <div class="form-group">
                <label for="password">Contraseña Secreta *</label>
                <input type="password" id="password" name="password" placeholder="Ingresa la contraseña a almacenar" required>
                <small class="text-muted">Puedes guardar la contraseña tal como la usas.</small>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- URL del Sitio -->
            <div class="form-group">
                <label for="url">URL o Enlace de Acceso (Opcional)</label>
                <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://ejemplo.com/login">
                @error('url')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notas Adicionales -->
            <div class="form-group">
                <label for="notas">Notas Seguras (Opcional)</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Preguntas de seguridad, códigos PIN u observaciones">{{ old('notas') }}</textarea>
                @error('notas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Credencial</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
const vaultPanel = document.getElementById('new-vault-panel');
const categorySelect = document.getElementById('categoria_id');
const nameInput = document.getElementById('quick-vault-name');
const descriptionInput = document.getElementById('quick-vault-description');
const status = document.getElementById('quick-vault-status');

document.getElementById('toggle-new-vault')?.addEventListener('click', () => {
    vaultPanel.hidden = !vaultPanel.hidden;
    if (!vaultPanel.hidden) nameInput.focus();
});
document.getElementById('cancel-quick-vault')?.addEventListener('click', () => {
    vaultPanel.hidden = true;
    status.textContent = '';
});
document.getElementById('save-quick-vault')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;
    const nameError = document.getElementById('quick-vault-name-error');
    const descriptionError = document.getElementById('quick-vault-description-error');
    nameError.hidden = true;
    descriptionError.hidden = true;
    status.textContent = 'Guardando…';
    button.disabled = true;

    try {
        const response = await fetch(@json(route('categorias.quick-store')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ nombre: nameInput.value, descripcion: descriptionInput.value }),
        });
        const data = await response.json();

        if (!response.ok) {
            nameError.textContent = data.errors?.nombre?.[0] ?? '';
            descriptionError.textContent = data.errors?.descripcion?.[0] ?? '';
            nameError.hidden = !data.errors?.nombre;
            descriptionError.hidden = !data.errors?.descripcion;
            status.textContent = data.message ?? 'No se pudo crear la bóveda.';
            return;
        }

        const option = new Option(data.nombre, data.id, true, true);
        categorySelect.add(option);
        categorySelect.value = String(data.id);
        status.textContent = 'Bóveda creada y seleccionada.';
        vaultPanel.hidden = true;
    } catch (error) {
        status.textContent = 'No se pudo conectar. Inténtalo de nuevo.';
    } finally {
        button.disabled = false;
    }
});
</script>
@endsection

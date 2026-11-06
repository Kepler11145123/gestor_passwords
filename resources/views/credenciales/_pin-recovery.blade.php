<div class="card" style="border-color:#c7c9ff; background:#f8f8ff">
    <h2 style="font-size:1.05rem; margin-bottom:.45rem">PIN restablecido</h2>
    <p class="text-muted" style="margin-bottom:1rem">La contraseña maestra verificó tu identidad y eliminó el PIN anterior. Puedes configurar uno nuevo o continuar sin PIN.</p>
    <form method="POST" action="{{ route('credenciales.pin-recovery.store', $credencial->id_credencial) }}">
        @csrf
        <div class="form-group">
            <label for="recovery_pin">Nuevo PIN de 4 dígitos</label>
            <input type="password" id="recovery_pin" name="pin" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
            @error('pin')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="recovery_pin_confirmation">Confirmar nuevo PIN</label>
            <input type="password" id="recovery_pin_confirmation" name="pin_confirmation" inputmode="numeric" pattern="[0-9]{4}" minlength="4" maxlength="4" autocomplete="new-password" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar PIN nuevo</button>
    </form>
    <form method="POST" action="{{ route('credenciales.pin-recovery.skip', $credencial->id_credencial) }}" style="margin-top:.75rem">
        @csrf
        <button type="submit" class="btn btn-secondary">Continuar sin PIN</button>
    </form>
</div>

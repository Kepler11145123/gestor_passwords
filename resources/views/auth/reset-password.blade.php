<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | BóvedaSegura</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: linear-gradient(135deg, #0f172a, #1e3a8a); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .brand { text-align: center; margin-bottom: 1.75rem; }
        .brand a { text-decoration: none; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .brand a span { color: #2563eb; }
        h1 { font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; color: #111827; transition: border-color 0.2s; }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        .error { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; }
        .btn-submit { width: 100%; background: #2563eb; color: #fff; padding: 0.8rem; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <a href="{{ route('landing') }}">🛡️ Bóveda<span>Segura</span></a>
    </div>

    <h1>Nueva Contraseña</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la nueva clave" required>
        </div>

        <button type="submit" class="btn-submit">Restablecer Contraseña</button>
    </form>
</div>
</body>
</html>

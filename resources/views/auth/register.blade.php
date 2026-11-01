<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta | BóvedaSegura</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: linear-gradient(135deg, #0f172a, #1e3a8a); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .brand { text-align: center; margin-bottom: 1.75rem; }
        .brand a { text-decoration: none; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .brand a span { color: #2563eb; }
        .brand p { color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem; }
        h1 { font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; color: #111827; transition: border-color 0.2s; }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        .error { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .hint { font-size: 0.78rem; color: #9ca3af; margin-top: 0.25rem; }
        .btn-submit { width: 100%; background: #2563eb; color: #fff; padding: 0.8rem; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { background: #1d4ed8; }
        .login-link { text-align: center; font-size: 0.9rem; color: #6b7280; margin-top: 1.25rem; }
        .login-link a { color: #2563eb; font-weight: 600; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <a href="{{ route('landing') }}">🛡️ Bóveda<span>Segura</span></a>
        <p>Crea tu cuenta gratuita</p>
    </div>

    <h1>Registro</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nombre Completo *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" required autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono (Opcional)</label>
            <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="+1 234 567 8900">
            @error('telefono') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Contraseña *</label>
                <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la clave" required>
            </div>
        </div>
        <p class="hint">La contraseña debe tener al menos 8 caracteres.</p>

        <button type="submit" class="btn-submit">Crear mi Cuenta</button>
    </form>

    <p class="login-link">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia Sesión</a>
    </p>
</div>
</body>
</html>

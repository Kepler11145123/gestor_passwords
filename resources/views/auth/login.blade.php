<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | BóvedaSegura</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: linear-gradient(135deg, #0f172a, #1e3a8a); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .brand { text-align: center; margin-bottom: 2rem; }
        .brand a { text-decoration: none; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .brand a span { color: #2563eb; }
        .brand p { color: #6b7280; font-size: 0.9rem; margin-top: 0.25rem; }
        h1 { font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        input { width: 100%; padding: 0.7rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; color: #111827; transition: border-color 0.2s; }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        .error { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; }
        .form-extras { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; font-size: 0.85rem; }
        .remember { display: flex; align-items: center; gap: 0.4rem; color: #374151; }
        .forgot { color: #2563eb; text-decoration: none; }
        .forgot:hover { text-decoration: underline; }
        .btn-submit { width: 100%; background: #2563eb; color: #fff; padding: 0.8rem; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #1d4ed8; }
        .divider { text-align: center; color: #9ca3af; font-size: 0.85rem; margin: 1.25rem 0; position: relative; }
        .divider::before, .divider::after { content: ''; position: absolute; top: 50%; width: 42%; height: 1px; background: #e5e7eb; }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .register-link { text-align: center; font-size: 0.9rem; color: #6b7280; }
        .register-link a { color: #2563eb; font-weight: 600; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <a href="{{ route('landing') }}">🛡️ Bóveda<span>Segura</span></a>
        <p>Gestor de Contraseñas</p>
    </div>

    <h1>Iniciar Sesión</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" required autofocus>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-extras">
            <label class="remember">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Recordarme
            </label>
            <a href="{{ route('password.request') }}" class="forgot">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn-submit">Entrar a mi Bóveda</button>
    </form>

    <div class="divider">o</div>

    <p class="register-link">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
    </p>
</div>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: '¡Listo!', text: "{{ session('success') }}", timer: 3500, showConfirmButton: false });
    @endif
</script>
</body>
</html>

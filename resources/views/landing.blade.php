<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bóveda Segura — Gestor de Contraseñas</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --text: #1f2937;
            --muted: #6b7280;
            --bg: #f4f6f9;
            --card: #ffffff;
            --border: #e5e7eb;
            --radius: 10px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: #0f172a; color: #fff; min-height: 100vh; }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(37,99,235,0.15);
            top: -200px; right: -200px;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            bottom: -100px; left: -100px;
        }

        /* NAV */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 3rem;
            position: relative;
            z-index: 10;
        }
        .brand { font-size: 1.4rem; font-weight: 700; color: #fff; text-decoration: none; }
        .brand span { color: #60a5fa; }
        .nav-actions { display: flex; gap: 0.75rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.2rem; border-radius: 8px; font-size: 0.9rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s ease; }
        .btn-outline { border: 1px solid rgba(255,255,255,0.3); color: #fff; background: transparent; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-lg { padding: 0.85rem 2rem; font-size: 1rem; border-radius: 10px; }

        /* HERO CONTENT */
        .hero-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 2rem;
            position: relative;
            z-index: 10;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(37,99,235,0.3);
            border: 1px solid rgba(96,165,250,0.4);
            color: #93c5fd;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            letter-spacing: -0.02em;
        }
        .hero-title span { color: #60a5fa; }
        .hero-sub {
            font-size: 1.15rem;
            color: #94a3b8;
            max-width: 600px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }

        /* FEATURES */
        .features {
            background: #ffffff;
            color: #1f2937;
            padding: 5rem 2rem;
        }
        .features-inner { max-width: 1100px; margin: 0 auto; }
        .features-title { text-align: center; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .features-sub { text-align: center; color: #6b7280; margin-bottom: 3rem; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .feature-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.75rem;
        }
        .feature-icon { font-size: 2rem; margin-bottom: 1rem; }
        .feature-card h3 { font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600; }
        .feature-card p { color: #6b7280; font-size: 0.9rem; line-height: 1.6; }

        /* FOOTER */
        footer {
            background: #0f172a;
            text-align: center;
            padding: 1.5rem;
            color: #475569;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<!-- HERO / LANDING -->
<section class="hero">
    <nav>
        <a href="{{ route('landing') }}" class="brand">🛡️ Bóveda<span>Segura</span></a>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-outline">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Crear Cuenta</a>
        </div>
    </nav>

    <div class="hero-content">
        <div class="hero-badge">🔒 Cifrado AES-256-CBC nativo de Laravel</div>
        <h1 class="hero-title">
            Gestiona tus contraseñas<br>con <span>total seguridad</span>
        </h1>
        <p class="hero-sub">
            Organiza todas tus credenciales en bóvedas cifradas, verificalas frente a bases de datos de filtraciones y accede a tus datos desde cualquier lugar.
        </p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Comenzar gratis →</a>
            <a href="{{ route('login') }}" class="btn btn-outline btn-lg">Iniciar Sesión</a>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="features-inner">
        <h2 class="features-title">¿Por qué BóvedaSegura?</h2>
        <p class="features-sub">Todo lo que necesitas para proteger tus contraseñas, en una sola aplicación.</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Cifrado de extremo a extremo</h3>
                <p>Todas las contraseñas son encriptadas con AES-256-CBC antes de almacenarse. Ni siquiera el servidor puede leerlas sin tu clave.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📁</div>
                <h3>Organización en Bóvedas</h3>
                <p>Agrupa tus credenciales por categorías: Trabajo, Personal, Finanzas, Redes Sociales. Encontrarás lo que buscas en segundos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚠️</div>
                <h3>Detector de filtraciones</h3>
                <p>Conectamos con HaveIBeenPwned para verificar si alguna de tus contraseñas ha aparecido en filtraciones de datos masivas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Dashboard interactivo</h3>
                <p>Visualiza el estado de tu seguridad con gráficos estadísticos en tiempo real. Identifica riesgos de un vistazo.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📄</div>
                <h3>Exportación PDF</h3>
                <p>Descarga informes de seguridad en PDF para tus registros o para compartir con tu equipo de forma segura.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Construido con Laravel</h3>
                <p>Backend robusto con PHP 8.3 + Laravel 13, modelos Eloquent optimizados, API REST propia y PostgreSQL como motor de BD.</p>
            </div>
        </div>
    </div>
</section>

<footer>
    <p>BóvedaSegura &copy; {{ date('Y') }} — Gestor de Contraseñas Seguro. Construido con Laravel 13 + PostgreSQL.</p>
</footer>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: '¡Listo!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
    @endif
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>SecureVault — Gestor de Contraseñas</title>
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
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(300px, .9fr);
            align-items: center;
            gap: 4rem;
            max-width: 1240px;
            width: 100%;
            margin: 0 auto;
            text-align: left;
            padding: 3rem 2rem 5rem;
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
        .hero-title span { color: #81e4dc; }
        .hero-sub {
            font-size: 1.15rem;
            color: #94a3b8;
            max-width: 600px;
            line-height: 1.7;
            margin-bottom: 2rem;
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
        .feature-card h3 { font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600; }
        .feature-card p { color: #6b7280; font-size: 0.9rem; line-height: 1.6; }
        .securevault-brand { display:inline-flex; align-items:center; gap:.7rem; color:#fff; text-decoration:none; font-size:1.35rem; font-weight:800; letter-spacing:-.045em; }
        .securevault-mark { width:42px; height:42px; }
        .securevault-brand .securevault-word > span { color:#82e4dc; }
        .nav-actions { align-items:center; }
        .btn-primary { background:linear-gradient(135deg,#6a6cf0,#5051d8); box-shadow:0 10px 25px rgba(54,57,177,.25); }
        .btn-primary:hover { background:linear-gradient(135deg,#7779fb,#5e60e8); transform:translateY(-1px); }
        .hero-art { position:relative; min-height:400px; display:grid; place-items:center; }
        .vault-orbit { position:absolute; width:330px;height:330px;border:1px solid rgba(130,228,220,.2);border-radius:50%;box-shadow:0 0 0 32px rgba(130,228,220,.025),0 0 0 64px rgba(130,228,220,.02); }
        .vault-panel { position:relative;width:min(100%,390px);padding:1.4rem;border:1px solid rgba(255,255,255,.16);border-radius:24px;background:linear-gradient(145deg,rgba(255,255,255,.16),rgba(255,255,255,.07));box-shadow:0 30px 80px rgba(3,8,34,.35);backdrop-filter:blur(16px);transform:rotate(2deg); }
        .vault-panel-head { display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;color:#dfe7ff;font-size:.85rem; }
        .vault-panel-stat { display:flex;align-items:center;gap:1rem;padding:1rem;border-radius:16px;background:rgba(9,17,52,.42);margin-top:.7rem;color:#f7f8ff; }
        .vault-panel-icon { width:42px;height:42px;display:grid;place-items:center;border-radius:13px;background:rgba(130,228,220,.13);color:#8be9df; }
        .vault-panel-icon .ui-icon { width:22px;height:22px; }
        .vault-panel-stat small { display:block;color:#9ca8ce;margin-top:.18rem; }
        .feature-icon { width:46px;height:46px;display:grid;place-items:center;border-radius:14px;background:#efefff;color:#595bd7;margin-bottom:1.1rem; }
        .feature-icon .ui-icon { width:23px;height:23px; }
        .feature-card { transition:transform .2s,box-shadow .2s; }
        .feature-card:hover { transform:translateY(-4px);box-shadow:0 16px 35px rgba(37,43,93,.1); }
        @media(max-width:800px) { nav{padding:1rem 1.25rem}.hero-content{grid-template-columns:1fr;gap:1rem;text-align:center;padding:3rem 1.25rem}.hero-actions{justify-content:center}.hero-art{min-height:310px}.vault-panel{max-width:350px}.hero-title{font-size:clamp(2.2rem,10vw,3.4rem)} }

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
        <x-brand-logo :href="route('landing')" :light="true" />
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-outline">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta</a>
        </div>
    </nav>

    <div class="hero-content">
        <div>
        <h1 class="hero-title">
            Tus claves, bajo<br>tu <span>control</span>
        </h1>
        <p class="hero-sub">
            SecureVault mantiene tus credenciales organizadas y protegidas, con una capa adicional de confirmación antes de revelar sus datos.
        </p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Crear mi bóveda</a>
            <a href="{{ route('login') }}" class="btn btn-outline btn-lg">Ya tengo una cuenta</a>
        </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="vault-orbit"></div>
            <div class="vault-panel">
                <div class="vault-panel-head"><span>Tu espacio seguro</span><x-icon name="shield"/></div>
                <div class="vault-panel-stat"><span class="vault-panel-icon"><x-icon name="key"/></span><span><strong>Credenciales</strong><small>Ordenadas por tus categorías</small></span></div>
                <div class="vault-panel-stat"><span class="vault-panel-icon"><x-icon name="scan"/></span><span><strong>Revisión de seguridad</strong><small>Comprueba posibles filtraciones</small></span></div>
                <div class="vault-panel-stat"><span class="vault-panel-icon"><x-icon name="lock"/></span><span><strong>Acceso con PIN</strong><small>Confirma antes de ver tus datos</small></span></div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="features-inner">
        <h2 class="features-title">Diseñado para darte tranquilidad</h2>
        <p class="features-sub">Todo lo que necesitas para proteger tus contraseñas, en una sola aplicación.</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="shield"/></div>
                <h3>Acceso protegido</h3>
                <p>Protege tus credenciales con una cuenta personal y una clave adicional para consultarlas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="vault"/></div>
                <h3>Organización en Bóvedas</h3>
                <p>Agrupa tus credenciales por categorías: Trabajo, Personal, Finanzas, Redes Sociales. Encontrarás lo que buscas en segundos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="scan"/></div>
                <h3>Detector de filtraciones</h3>
                <p>Conectamos con HaveIBeenPwned para verificar si alguna de tus contraseñas ha aparecido en filtraciones de datos masivas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="dashboard"/></div>
                <h3>Dashboard interactivo</h3>
                <p>Visualiza el estado de tu seguridad con gráficos estadísticos en tiempo real. Identifica riesgos de un vistazo.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="file"/></div>
                <h3>Exportación PDF</h3>
                <p>Descarga informes de seguridad en PDF para tus registros o para compartir con tu equipo de forma segura.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><x-icon name="spark"/></div>
                <h3>Tu espacio, a tu medida</h3>
                <p>Una experiencia clara y flexible para que organices tus credenciales como mejor te funcione.</p>
            </div>
        </div>
    </div>
</section>

<footer>
    <p>SecureVault &copy; {{ date('Y') }} — Un espacio más seguro para tus credenciales.</p>
</footer>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: '¡Listo!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
    @endif
</script>
</body>
</html>

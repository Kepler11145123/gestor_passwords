<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('titulo', 'Panel') | SecureVault</title>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <style>
        :root {
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --success: #16a34a;
            --warning: #d97706;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --radius: 8px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #111827;
            color: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .nav-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-links a {
            color: #d1d5db;
            text-decoration: none;
            padding: 0.5rem 0.85rem;
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        main {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111827;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
        }

        th, td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f9fafb;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        /* Botones */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: var(--radius);
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            gap: 0.35rem;
        }

        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .btn-danger {
            background-color: var(--danger);
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        /* Formularios */
        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
        }

        input[type="text"],
        input[type="password"],
        input[type="url"],
        select,
        textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            background-color: #ffffff;
            color: var(--text-main);
            transition: border-color 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-error {
            color: var(--danger);
            font-size: 0.82rem;
            margin-top: 0.3rem;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .text-muted {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        :root { --bg-color:#f4f6fb; --card-bg:#fff; --primary:#5b5ce2; --primary-hover:#4748c8; --text-main:#171a32; --text-muted:#737891; --border-color:#e7e9f2; --radius:16px; }
        body { background:radial-gradient(ellipse at 15% 0%, #edf0ff 0, transparent 36%), var(--bg-color); }
        header { background:rgba(255,255,255,.92); color:var(--text-main); padding:.75rem 2rem; border-bottom:1px solid var(--border-color); box-shadow:0 8px 30px rgba(22,26,58,.04); backdrop-filter:blur(16px); position:sticky; top:0; z-index:50; }
        nav { max-width:1440px; }
        .securevault-brand { display:inline-flex; align-items:center; gap:.65rem; color:var(--text-main); text-decoration:none; font-size:1.22rem; font-weight:800; letter-spacing:-.045em; white-space:nowrap; }
        .securevault-brand .securevault-mark { width:38px; height:38px; flex:none; }
        .securevault-word > span { color:#5b5ce2; }
        .nav-links { gap:.35rem; }
        .nav-links a { display:inline-flex; align-items:center; gap:.5rem; color:#666c87; padding:.62rem .82rem; border-radius:11px; font-size:.88rem; font-weight:650; }
        .nav-links a:hover,.nav-links a.active { color:#4e50cd; background:#f0f0ff; }
        .ui-icon { width:19px; height:19px; flex:none; }
        .user-menu-trigger { border:1px solid var(--border-color)!important; border-radius:999px!important; padding:.25rem .6rem .25rem .28rem!important; color:var(--text-main)!important; }
        .user-dropdown { background:#fff!important; border:1px solid var(--border-color); box-shadow:0 18px 50px rgba(22,26,58,.15)!important; }
        .user-dropdown a,.user-dropdown button { color:#3a3e59!important; }
        main { max-width:1380px; padding:0 2rem; margin:2.25rem auto; }
        .page-title { color:#191d3b; font-size:clamp(1.5rem,2vw,2rem); letter-spacing:-.04em; }
        .page-header { margin-bottom:1.8rem; }
        .card { border-color:rgba(221,224,239,.9); border-radius:18px; box-shadow:0 10px 35px rgba(30,35,80,.055); padding:1.55rem; }
        .btn { border-radius:11px; font-weight:650; padding:.65rem 1rem; }
        .btn-primary { background:linear-gradient(135deg,#6668ed,#4f50d5); box-shadow:0 7px 17px rgba(85,86,219,.2); }
        .btn-primary:hover { background:linear-gradient(135deg,#5557dc,#4142bf); transform:translateY(-1px); }
        .btn-secondary { background:#f6f7fc; color:#414663; border-color:#e6e8f2; }
        .badge { background:#eeeeff; color:#5052cb; border-radius:8px; padding:.35rem .6rem; }
        input[type="email"],input[type="file"],input[type="number"],input[type="date"] { width:100%; padding:.65rem .85rem; border:1px solid var(--border-color); border-radius:var(--radius); background:#fff; color:var(--text-main); }
        table { border-spacing:0; }
        th { background:#f7f8fc; color:#747993; }
        tbody tr:hover { background:#f8f8ff; }
        .nav-profile { display:flex;align-items:center;gap:.65rem; }
        .nav-profile img { width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #ececff; }
        .dropdown-link { display:flex!important;align-items:center;gap:.65rem; }
        .user-dropdown a:hover,.user-dropdown button:hover { background:#f1f2ff; }
        @media(max-width:1050px) { header{padding:.75rem 1rem}.nav-links{order:3;width:100%;overflow-x:auto}.nav-links a{white-space:nowrap} }
        @media(max-width:640px) { main{padding:0 1rem;margin:1.25rem auto}.page-header{align-items:flex-start}.card{padding:1.1rem}.nav-profile img{width:34px;height:34px} }
    </style>
</head>
<body>
    <header>
        <nav>
            <x-brand-logo :href="route('dashboard.index')" />
            <div class="nav-links">
                <a class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="{{ route('dashboard.index') }}"><x-icon name="dashboard"/>Inicio</a>
                <a class="{{ request()->routeIs('categorias.*', 'credenciales.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}"><x-icon name="vault"/>Bóvedas</a>
                <a class="{{ request()->routeIs('hibp.*') ? 'active' : '' }}" href="{{ route('hibp.index') }}"><x-icon name="scan"/>Filtraciones</a>
                <a class="{{ request()->routeIs('informe.pdf') ? 'active' : '' }}" href="{{ route('informe.pdf') }}"><x-icon name="file"/>Informe</a>
            </div>
            <!-- Menú usuario activo -->
            <div class="nav-profile" style="position: relative;">
                <a href="{{ route('perfil.edit') }}" aria-label="Cambiar foto de perfil"><img src="{{ auth()->user()->avatarUrl() }}" alt="Foto de perfil"></a>
                <div style="position: relative;">
                    <button class="user-menu-trigger" onclick="toggleUserMenu()" style="background:transparent; border:none; cursor:pointer; font-size: 0.9rem; display:flex; align-items:center; gap:0.35rem;">
                        {{ auth()->user()->name }}
                    </button>
                    <div id="userMenu" class="user-dropdown" style="display:none; position:absolute; right:0; top:calc(100% + 0.5rem); border-radius:12px; min-width:190px; overflow:hidden; z-index:100;">
                        <a href="{{ route('perfil.edit') }}" class="dropdown-link" style="display:block; padding:0.7rem 1rem; text-decoration:none; font-size:0.9rem; transition:background 0.15s;">
                            <x-icon name="user"/>Mi perfil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-link" style="width:100%; text-align:left; padding:0.7rem 1rem; background:transparent; border:none; cursor:pointer; font-size:0.9rem; border-top: 1px solid var(--border-color);">
                                <x-icon name="logout"/>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('contenido')
    </main>

    <!-- SweetAlert2: Notificaciones y confirmaciones automáticas -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Notificación Flash de Éxito
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: "{{ session('success') }}",
                    timer: 3500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            @endif

            // Notificación Flash de Error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}"
                });
            @endif

            // Interceptación de formularios de eliminación con confirmación SweetAlert2
            const deleteForms = document.querySelectorAll('.form-eliminar');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Confirmar eliminación?',
                        text: 'Esta acción no se puede deshacer y eliminará los datos asociados.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @yield('scripts')

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            if (menu && !e.target.closest('#userMenu') && !e.target.closest('button[onclick]')) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>
</html>

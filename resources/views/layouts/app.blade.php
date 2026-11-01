<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Gestor de Contraseñas') | Bóveda Segura</title>
    
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
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('dashboard.index') }}" class="nav-brand">
                🛡️ Gestor de Contraseñas
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <a href="{{ route('categorias.index') }}">Bóvedas</a>
                <a href="{{ route('credenciales.index') }}">Credenciales</a>
                <a href="{{ route('hibp.index') }}">🔍 Verificar Filtración</a>
                <a href="{{ route('informe.pdf') }}" target="_blank">📄 PDF</a>
            </div>
            <!-- Menú usuario activo -->
            <div style="position: relative; display: flex; align-items: center; gap: 0.75rem;">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border: 2px solid rgba(255,255,255,0.3);">
                <div style="position: relative;">
                    <button onclick="toggleUserMenu()" style="background:transparent; border:none; color:#d1d5db; cursor:pointer; font-size: 0.9rem; display:flex; align-items:center; gap:0.35rem;">
                        {{ auth()->user()->name }} <span>▾</span>
                    </button>
                    <div id="userMenu" style="display:none; position:absolute; right:0; top:calc(100% + 0.5rem); background:#1f2937; border-radius:8px; min-width:180px; overflow:hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.4); z-index:100;">
                        <a href="{{ route('perfil.edit') }}" style="display:block; padding:0.7rem 1rem; color:#d1d5db; text-decoration:none; font-size:0.9rem; transition:background 0.15s;" onmouseover="this.style.background='#374151'" onmouseout="this.style.background='transparent'">
                            👤 Editar Perfil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" style="width:100%; text-align:left; padding:0.7rem 1rem; color:#f87171; background:transparent; border:none; cursor:pointer; font-size:0.9rem; border-top: 1px solid #374151;" onmouseover="this.style.background='#374151'" onmouseout="this.style.background='transparent'">
                                🚪 Cerrar Sesión
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

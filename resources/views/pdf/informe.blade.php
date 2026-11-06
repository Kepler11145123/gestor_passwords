<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Seguridad</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 24px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1e3a8a;
            font-size: 22px;
            margin: 0 0 4px 0;
        }
        .header p {
            color: #6b7280;
            margin: 0;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .metrics {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin: 0 -10px 20px -10px;
        }
        .metrics td {
            width: 33%;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        .metrics .label {
            display: block;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .metrics .value {
            display: block;
            color: #1e3a8a;
            font-size: 24px;
            font-weight: bold;
            margin-top: 4px;
        }
        h2 {
            color: #374151;
            font-size: 14px;
            margin: 22px 0 8px 0;
            border-left: 3px solid #2563eb;
            padding-left: 8px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data th {
            background: #f3f4f6;
            color: #374151;
            font-size: 10px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 7px 8px;
            border-bottom: 1px solid #d1d5db;
        }
        table.data td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table.data tr:nth-child(even) td {
            background: #f9fafb;
        }
        .muted {
            color: #6b7280;
            font-size: 10px;
        }
        .empty {
            text-align: center;
            color: #6b7280;
            padding: 14px;
        }
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    <h1>Informe de Seguridad</h1>
                    <p>SecureVault — Gestor de Contraseñas</p>
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <span class="badge">Generado: {{ $fecha }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="metrics">
        <tr>
            <td>
                <span class="label">Total de Bóvedas</span>
                <span class="value">{{ $totalCategorias }}</span>
            </td>
            <td>
                <span class="label">Credenciales guardadas</span>
                <span class="value">{{ $totalCredenciales }}</span>
            </td>
        </tr>
    </table>

    <h2>Credenciales por Bóveda</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Bóveda / Categoría</th>
                <th style="text-align:right;">Credenciales</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estadisticas as $fila)
                <tr>
                    <td>{{ $fila->nombre }}</td>
                    <td style="text-align:right;">{{ $fila->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty">No hay bóvedas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Últimas Credenciales Registradas</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Identificador</th>
                <th>Bóveda</th>
                <th>Título / Servicio</th>
                <th>Usuario</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimasCredenciales as $credencial)
                <tr>
                    <td>{{ $credencial->id_credencial }}</td>
                    <td>{{ $credencial->categoria->nombre ?? 'Sin Bóveda' }}</td>
                    <td>{{ $credencial->titulo }}</td>
                    <td>{{ $credencial->usuario }}</td>
                    <td>{{ $credencial->created_at ? $credencial->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">No hay credenciales registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="muted">Este informe no incluye contraseñas ni notas sensibles.</p>

    <div class="footer">
        SecureVault © {{ date('Y') }} — Documento generado automáticamente el {{ $fecha }}.
    </div>
</body>
</html>

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Credencial;
use Illuminate\Http\JsonResponse;

class EstadisticasApiController extends Controller
{
    /**
     * GET /api/v1/estadisticas
     * Retorna las métricas generales del gestor.
     */
    public function estadisticas(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'data'   => [
                'total_bovedas'      => auth()->user()->categorias()->count(),
                'total_credenciales' => auth()->user()->credenciales()->count(),
                'por_boveda'         => Categoria::obtenerConteoCredencialesPorCategoria(),
            ],
        ]);
    }

    /**
     * GET /api/v1/bovedas
     * Lista todas las bóvedas con su conteo de credenciales.
     */
    public function bovedas(): JsonResponse
    {
        $bovedas = auth()->user()->categorias()
            ->withCount(['credenciales' => fn ($query) => $query->where('credenciales.user_id', auth()->id())])
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre', 'descripcion', 'created_at']);

        return response()->json([
            'status' => 'ok',
            'total'  => $bovedas->count(),
            'data'   => $bovedas,
        ]);
    }
}

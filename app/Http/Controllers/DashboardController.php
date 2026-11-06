<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard principal).
     * Delega la consulta pesada al Modelo cumpliendo SRP.
     */
    public function index(): View
    {
        // 1. Consulta pesada en el modelo con select, count y groupBy
        $estadisticas = auth()->user()->categorias()->leftJoin('credenciales', function ($join) {
            $join->on('categorias.id', '=', 'credenciales.categoria_id')
                ->where('credenciales.user_id', auth()->id());
        })->select('categorias.nombre')->selectRaw('COUNT(credenciales.id_credencial) as total')
            ->groupBy('categorias.id', 'categorias.nombre')->orderBy('categorias.nombre')->get();

        // 2. Extracción dinámica de etiquetas y métricas numéricas (cero datos quemados)
        $chartLabels = $estadisticas->pluck('nombre');
        $chartData   = $estadisticas->pluck('total');

        // 3. Tarjetas métricas de resumen para la vista del dashboard
        $totalCategorias     = auth()->user()->categorias()->count();
        $totalCredenciales   = auth()->user()->credenciales()->count();
        $bovedas = auth()->user()->categorias()
            ->withCount(['credenciales' => fn ($query) => $query->where('credenciales.user_id', auth()->id())])
            ->orderBy('nombre')
            ->get();

        return view('dashboard.index', compact(
            'chartLabels',
            'chartData',
            'totalCategorias',
            'totalCredenciales',
            'bovedas'
        ));
    }

    /**
     * Métodos estándar de Resource Controller para cumplimiento de arquitectura RESTful.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }
}

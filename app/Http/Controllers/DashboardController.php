<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Credencial;
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
        $estadisticas = Categoria::obtenerConteoCredencialesPorCategoria();

        // 2. Extracción dinámica de etiquetas y métricas numéricas (cero datos quemados)
        $chartLabels = $estadisticas->pluck('nombre');
        $chartData   = $estadisticas->pluck('total');

        // 3. Tarjetas métricas de resumen para la vista del dashboard
        $totalCategorias     = Categoria::count();
        $totalCredenciales   = Credencial::count();
        $ultimasCredenciales = Credencial::with('categoria')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'chartLabels',
            'chartData',
            'totalCategorias',
            'totalCredenciales',
            'ultimasCredenciales'
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

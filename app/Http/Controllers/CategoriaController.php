<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Carga el listado y las estadísticas dinámicas para Chart.js delegadas al modelo.
     */
    public function index(): View
    {
        $categorias = Categoria::withCount('credenciales')->orderBy('nombre', 'asc')->get();
        $estadisticas = Categoria::obtenerEstadisticasParaGrafico();

        return view('categorias.index', compact('categorias', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        Categoria::create($datosValidados);

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $categoria = Categoria::with('credenciales')->findOrFail($id);

        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $categoria = Categoria::findOrFail($id);

        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $categoria = Categoria::findOrFail($id);

        $datosValidados = $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria->update($datosValidados);

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente junto con sus credenciales asociadas.');
    }
}

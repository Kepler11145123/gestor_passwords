<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Carga el listado y las estadísticas dinámicas para Chart.js delegadas al modelo.
     */
    public function index(): View
    {
        $categorias = auth()->user()->categorias()
            ->withCount(['credenciales' => fn ($query) => $query->where('credenciales.user_id', auth()->id())])
            ->orderBy('nombre', 'asc')
            ->get();
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
            'nombre'      => 'required|string|max:100|unique:categorias,nombre,NULL,id,user_id,'.auth()->id(),
            'descripcion' => 'nullable|string|max:500',
        ]);

        auth()->user()->categorias()->create($datosValidados);

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function quickStore(Request $request): JsonResponse
    {
        $datosValidados = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('categorias', 'nombre')->where('user_id', auth()->id())],
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria = auth()->user()->categorias()->create($datosValidados);

        return response()->json([
            'id' => $categoria->id,
            'nombre' => $categoria->nombre,
            'descripcion' => $categoria->descripcion,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $categoria = auth()->user()->categorias()->findOrFail($id);
        $credenciales = auth()->user()->credenciales()
            ->where('categoria_id', $categoria->id)
            ->latest()
            ->get();

        return view('categorias.show', compact('categoria', 'credenciales'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $categoria = auth()->user()->categorias()->findOrFail($id);

        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $categoria = auth()->user()->categorias()->findOrFail($id);

        $datosValidados = $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id . ',id,user_id,'.auth()->id(),
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
        $categoria = auth()->user()->categorias()->findOrFail($id);
        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente junto con sus credenciales asociadas.');
    }
}

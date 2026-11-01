<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Credencial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CredencialController extends Controller
{
    /**
     * Display a listing of the resource.
     * Carga las credenciales mediante eager loading para optimizar las consultas.
     */
    public function index(): View
    {
        $credenciales = Credencial::with('categoria')->latest()->get();

        return view('credenciales.index', compact('credenciales'));
    }

    /**
     * Show the form for creating a new resource.
     * Carga dinámicamente las categorías disponibles (cero datos quemados en select).
     */
    public function create(): View
    {
        $categorias = Categoria::select('id', 'nombre')->orderBy('nombre', 'asc')->get();

        return view('credenciales.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     * Delega la lógica pesada de encriptación al Modelo (SRP).
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_credencial' => 'nullable|string|max:64|unique:credenciales,id_credencial',
            'categoria_id'  => 'required|exists:categorias,id',
            'titulo'        => 'required|string|max:150',
            'usuario'       => 'required|string|max:150',
            'password'      => 'required|string|min:6',
            'url'           => 'nullable|url|max:255',
            'notas'         => 'nullable|string|max:1000',
        ]);

        // Delegación de la persistencia y cifrado al Modelo
        Credencial::registrarCredencial($datosValidados);

        return redirect()
            ->route('credenciales.index')
            ->with('success', 'Credencial registrada y cifrada con éxito.');
    }

    /**
     * Display the specified resource.
     * Obtiene la contraseña desencriptada a través del método del modelo.
     */
    public function show(string $id_credencial): View
    {
        $credencial = Credencial::with('categoria')->findOrFail($id_credencial);
        $passwordPlana = $credencial->obtenerPasswordDesencriptada();

        return view('credenciales.show', compact('credencial', 'passwordPlana'));
    }

    /**
     * Show the form for editing the specified resource.
     * Carga las categorías dinámicamente para la selección.
     */
    public function edit(string $id_credencial): View
    {
        $credencial = Credencial::findOrFail($id_credencial);
        $categorias = Categoria::select('id', 'nombre')->orderBy('nombre', 'asc')->get();

        return view('credenciales.edit', compact('credencial', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     * Delega la actualización y re-encriptación de la contraseña al Modelo (SRP).
     */
    public function update(Request $request, string $id_credencial): RedirectResponse
    {
        $credencial = Credencial::findOrFail($id_credencial);

        $datosValidados = $request->validate([
            'categoria_id'  => 'required|exists:categorias,id',
            'titulo'        => 'required|string|max:150',
            'usuario'       => 'required|string|max:150',
            'password'      => 'nullable|string|min:6', // Opcional al actualizar
            'url'           => 'nullable|url|max:255',
            'notas'         => 'nullable|string|max:1000',
        ]);

        // Delegación de actualización y re-cifrado condicional al Modelo
        $credencial->modificarCredencial($datosValidados);

        return redirect()
            ->route('credenciales.index')
            ->with('success', 'Credencial actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_credencial): RedirectResponse
    {
        $credencial = Credencial::findOrFail($id_credencial);
        $credencial->delete();

        return redirect()
            ->route('credenciales.index')
            ->with('success', 'Credencial eliminada permanentemente.');
    }
}

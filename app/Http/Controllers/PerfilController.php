<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PerfilController extends Controller
{
    /**
     * Muestra el formulario de edición del perfil del usuario activo.
     */
    public function edit(): View
    {
        $usuario = Auth::user();

        return view('perfil.edit', compact('usuario'));
    }

    /**
     * Actualiza los datos del perfil (nombre, correo, teléfono, avatar).
     */
    public function update(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,'.$usuario->id,
            'telefono' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Subida de imagen: almacena en storage/app/public/avatars
        if ($request->hasFile('avatar')) {
            // Elimina avatar anterior si existe
            if ($usuario->avatar) {
                Storage::disk('public')->delete($usuario->avatar);
            }
            $datos['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $usuario->update($datos);

        return redirect()->route('perfil.edit')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Actualiza la contraseña del usuario activo.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Auth::user();

        if (! Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        $usuario->update(['password' => Hash::make($request->password)]);

        return redirect()->route('perfil.edit')
            ->with('success', 'Contraseña de acceso actualizada correctamente.');
    }
}

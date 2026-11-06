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
    public function avatar(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $usuario = Auth::user();
        abort_unless($usuario->avatar && Storage::disk('public')->exists($usuario->avatar), 404);
        return Storage::disk('public')->response($usuario->avatar, null, [
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Muestra el formulario de edición del perfil del usuario activo.
     */
    public function edit(): View
    {
        $usuario = Auth::user();
        $tieneAvatar = $usuario->avatar && Storage::disk('public')->exists($usuario->avatar);

        return view('perfil.edit', compact('usuario', 'tieneAvatar'));
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

        if ($usuario->wasChanged('email')) {
            $usuario->forceFill(['email_verified_at' => null])->save();
            $usuario->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')->with('success', 'Actualizamos el correo. Verifica la nueva dirección para continuar.');
        }

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
            'password' => ['required', 'string', 'min:12', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
        ]);

        $usuario = Auth::user();

        if (! Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        $usuario->update(['password' => Hash::make($request->password)]);

        return redirect()->route('perfil.edit')
            ->with('success', 'Contraseña de acceso actualizada correctamente.');
    }

    public function updatePin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:enable,disable',
            'master_password' => 'required|string',
        ]);
        $usuario = Auth::user();

        if (! Hash::check($validated['master_password'], $usuario->password)) {
            return back()->withErrors(['master_password_pin' => 'La contraseña maestra no es correcta.']);
        }

        if ($validated['action'] === 'enable') {
            $pin = $request->validate([
                'vault_pin' => 'required|digits:4|confirmed',
            ])['vault_pin'];
            $usuario->forceFill(['vault_pin' => Hash::make($pin), 'vault_pin_prompt_dismissed' => false])->save();
            $mensaje = $usuario->wasChanged('vault_pin') ? 'PIN activado o actualizado correctamente.' : 'PIN confirmado.';
        } else {
            $usuario->forceFill(['vault_pin' => null, 'vault_pin_prompt_dismissed' => true])->save();
            $mensaje = 'PIN desactivado correctamente.';
        }
        $this->clearCredentialUnlocks();

        return redirect()->route('perfil.edit')->with('success', $mensaje);
    }

    public function removeAvatar(): RedirectResponse
    {
        $usuario = Auth::user();
        if ($usuario->avatar) {
            Storage::disk('public')->delete($usuario->avatar);
            $usuario->forceFill(['avatar' => null])->save();
        }

        return redirect()->route('perfil.edit')->with('success', 'Foto de perfil eliminada.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $usuario = Auth::user();
        $path = $validated['avatar']->store('avatars', 'public');

        if ($usuario->avatar) {
            Storage::disk('public')->delete($usuario->avatar);
        }
        $usuario->forceFill(['avatar' => $path])->save();

        return redirect()->route('perfil.edit')->with('success', 'Foto de perfil actualizada.');
    }

    public function pinOnboarding(): View|RedirectResponse
    {
        if (Auth::user()->vault_pin || Auth::user()->vault_pin_prompt_dismissed) {
            return redirect()->route('dashboard.index');
        }

        return view('seguridad.pin-onboarding');
    }

    public function savePinOnboarding(Request $request): RedirectResponse
    {
        $action = $request->validate(['action' => 'required|in:configure,skip'])['action'];

        if ($action === 'configure') {
            $pin = $request->validate([
                'vault_pin' => 'required|digits:4|confirmed',
            ])['vault_pin'];
            Auth::user()->forceFill(['vault_pin' => Hash::make($pin), 'vault_pin_prompt_dismissed' => false])->save();
        }

        return redirect()->route('dashboard.index')->with(
            'success',
            $action === 'configure' ? 'PIN configurado correctamente.' : 'Puedes configurar un PIN después desde tu perfil.'
        );
    }

    private function clearCredentialUnlocks(): void
    {
        $prefix = 'credential_unlock.'.Auth::id().'.';
        foreach (array_keys(session()->all()) as $key) {
            if (str_starts_with($key, $prefix)) {
                session()->forget($key);
            }
        }
        session()->forget(['pin_recovery_pending', 'pin_recovery_intent']);
    }
}

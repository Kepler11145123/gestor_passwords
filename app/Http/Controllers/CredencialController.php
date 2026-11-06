<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CredencialController extends Controller
{
    /**
     * Display a listing of the resource.
     * Carga las credenciales mediante eager loading para optimizar las consultas.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('categorias.index');
    }

    /**
     * Show the form for creating a new resource.
     * Carga dinámicamente las categorías disponibles (cero datos quemados en select).
     */
    public function create(Request $request): View
    {
        $categorias = auth()->user()->categorias()->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        $categoriaSeleccionada = old('categoria_id', $request->query('categoria_id'));

        return view('credenciales.create', compact('categorias', 'categoriaSeleccionada'));
    }

    /**
     * Store a newly created resource in storage.
     * Delega la lógica pesada de encriptación al Modelo (SRP).
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_credencial' => 'nullable|string|max:64|unique:credenciales,id_credencial',
            'categoria_id'  => ['required', Rule::exists('categorias', 'id')->where('user_id', auth()->id())],
            'titulo'        => 'required|string|max:150',
            'usuario'       => 'required|string|max:150',
            'password'      => 'required|string',
            'url'           => 'nullable|url|max:255',
            'notas'         => 'nullable|string|max:1000',
        ]);

        // The selected category must already belong to this account. The user
        // relationship assigns user_id, while categoria_id preserves the vault.
        $categoriaId = $datosValidados['categoria_id'];
        auth()->user()->credenciales()->create(array_merge($datosValidados, [
            'password' => \Illuminate\Support\Facades\Crypt::encryptString($datosValidados['password']),
        ]));

        return redirect()
            ->route('categorias.show', $categoriaId)
            ->with('success', 'Credencial registrada correctamente.');
    }

    /**
     * Display the specified resource.
     * Obtiene la contraseña desencriptada a través del método del modelo.
     */
    public function show(string $id_credencial): View
    {
        $credencial = auth()->user()->credenciales()->with('categoria')->findOrFail($id_credencial);
        $unlockKey = 'credential_unlock.'.auth()->id().'.'.$id_credencial;
        $pinRecoveryPending = session('pin_recovery_pending') === $id_credencial;
        $user = auth()->user();
        $credentialUnlocked = $user->vault_pin
            ? session($unlockKey, 0) >= now()->timestamp
            : ($user->vault_pin_prompt_dismissed || session($unlockKey, 0) >= now()->timestamp);
        $passwordPlana = $credentialUnlocked
            ? $credencial->obtenerPasswordDesencriptada()
            : null;
        $intent = request()->query('intent') === 'edit' ? 'edit' : 'show';

        return view('credenciales.show', compact('credencial', 'passwordPlana', 'credentialUnlocked', 'pinRecoveryPending', 'intent'));
    }

    public function reveal(Request $request, string $id_credencial): RedirectResponse
    {
        $request->validate(['intent' => 'nullable|in:show,edit']);
        $credential = auth()->user()->credenciales()->findOrFail($id_credencial);
        $intent = $request->input('intent', 'show');
        $user = auth()->user();

        if ($user->vault_pin && $request->filled('master_password')) {
            $request->validate(['master_password' => 'required|string']);
            if (! Hash::check($request->input('master_password'), $user->password)) {
                return back()->withErrors(['master_password' => 'La contraseña maestra no es correcta.']);
            }

            $user->forceFill(['vault_pin' => null, 'vault_pin_prompt_dismissed' => false])->save();
            session([
                'pin_recovery_pending' => $credential->id_credencial,
                'pin_recovery_intent' => $intent,
            ]);
        } elseif ($user->vault_pin) {
            $request->validate(['pin' => 'required|digits:4']);
            if (! $user->vault_pin || ! Hash::check($request->input('pin'), $user->vault_pin)) {
                return back()->withErrors(['pin' => 'El PIN no es correcto.']);
            }
        } elseif ($request->boolean('skip_pin')) {
            session()->forget(['pin_recovery_pending', 'pin_recovery_intent']);
        } else {
            $validated = $request->validate([
                'master_password' => 'required|string',
                'new_pin' => 'required|digits:4|confirmed',
            ]);
            if (! Hash::check($validated['master_password'], $user->password)) {
                return back()->withErrors(['master_password' => 'La contraseña maestra no es correcta.']);
            }
            $user->forceFill(['vault_pin' => Hash::make($validated['new_pin']), 'vault_pin_prompt_dismissed' => false])->save();
        }

        if (! $user->vault_pin && $request->boolean('skip_pin')) {
            $user->forceFill(['vault_pin_prompt_dismissed' => true])->save();
        }

        session(['credential_unlock.'.auth()->id().'.'.$credential->id_credencial => now()->addSeconds(30)->timestamp]);
        return $intent === 'edit'
            ? redirect()->route('credenciales.edit', $credential->id_credencial)
            : redirect()->route('credenciales.show', $credential->id_credencial);
    }

    /**
     * Show the form for editing the specified resource.
     * Carga las categorías dinámicamente para la selección.
     */
    public function edit(string $id_credencial): View|RedirectResponse
    {
        $credencial = auth()->user()->credenciales()->findOrFail($id_credencial);
        if ((auth()->user()->vault_pin || ! auth()->user()->vault_pin_prompt_dismissed)
            && session('credential_unlock.'.auth()->id().'.'.$id_credencial, 0) < now()->timestamp) {
            return redirect()->route('credenciales.show', ['id_credencial' => $id_credencial, 'intent' => 'edit']);
        }
        $categorias = auth()->user()->categorias()->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        $pinRecoveryPending = session('pin_recovery_pending') === $id_credencial;

        return view('credenciales.edit', compact('credencial', 'categorias', 'pinRecoveryPending'));
    }

    /**
     * Update the specified resource in storage.
     * Delega la actualización y re-encriptación de la contraseña al Modelo (SRP).
     */
    public function update(Request $request, string $id_credencial): RedirectResponse
    {
        $credencial = auth()->user()->credenciales()->findOrFail($id_credencial);
        if ((auth()->user()->vault_pin || ! auth()->user()->vault_pin_prompt_dismissed)
            && session('credential_unlock.'.auth()->id().'.'.$id_credencial, 0) < now()->timestamp) {
            return redirect()->route('credenciales.show', ['id_credencial' => $id_credencial, 'intent' => 'edit']);
        }

        $datosValidados = $request->validate([
            'categoria_id'  => ['required', Rule::exists('categorias', 'id')->where('user_id', auth()->id())],
            'titulo'        => 'required|string|max:150',
            'usuario'       => 'required|string|max:150',
            'password'      => 'nullable|string',
            'url'           => 'nullable|url|max:255',
            'notas'         => 'nullable|string|max:1000',
        ]);

        // Delegación de actualización y re-cifrado condicional al Modelo
        $credencial->modificarCredencial($datosValidados);

        return redirect()
            ->route('categorias.show', $credencial->categoria_id)
            ->with('success', 'Credencial actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_credencial): RedirectResponse
    {
        $credencial = auth()->user()->credenciales()->findOrFail($id_credencial);
        $categoriaId = $credencial->categoria_id;
        $credencial->delete();

        return redirect()
            ->route('categorias.show', $categoriaId)
            ->with('success', 'Credencial eliminada permanentemente.');
    }

    public function setRecoveredPin(Request $request, string $id_credencial): RedirectResponse
    {
        $credential = auth()->user()->credenciales()->findOrFail($id_credencial);
        abort_unless(session('pin_recovery_pending') === $credential->id_credencial, 403);

        $validated = $request->validate([
            'pin' => 'required|digits:4|confirmed',
        ]);

        auth()->user()->forceFill(['vault_pin' => Hash::make($validated['pin']), 'vault_pin_prompt_dismissed' => false])->save();
        $intent = session('pin_recovery_intent', 'show');
        session()->forget(['pin_recovery_pending', 'pin_recovery_intent']);
        session(['credential_unlock.'.auth()->id().'.'.$id_credencial => now()->addSeconds(30)->timestamp]);
        session(['credential_unlock.'.auth()->id().'.'.$id_credencial => now()->addSeconds(30)->timestamp]);

        return $intent === 'edit'
            ? redirect()->route('credenciales.edit', $id_credencial)->with('success', 'PIN actualizado correctamente.')
            : redirect()->route('credenciales.show', $id_credencial)->with('success', 'PIN actualizado correctamente.');
    }

    public function continueWithoutPin(string $id_credencial): RedirectResponse
    {
        auth()->user()->credenciales()->findOrFail($id_credencial);
        abort_unless(session('pin_recovery_pending') === $id_credencial, 403);
        $intent = session('pin_recovery_intent', 'show');
        auth()->user()->forceFill(['vault_pin_prompt_dismissed' => true])->save();
        session()->forget(['pin_recovery_pending', 'pin_recovery_intent']);

        return $intent === 'edit'
            ? redirect()->route('credenciales.edit', $id_credencial)
            : redirect()->route('credenciales.show', $id_credencial);
    }

    public function lock(string $id_credencial): \Illuminate\Http\Response
    {
        auth()->user()->credenciales()->findOrFail($id_credencial);
        session()->forget('credential_unlock.'.auth()->id().'.'.$id_credencial);

        return response()->noContent();
    }

    public function keepAlive(string $id_credencial): \Illuminate\Http\Response
    {
        auth()->user()->credenciales()->findOrFail($id_credencial);
        $unlockKey = 'credential_unlock.'.auth()->id().'.'.$id_credencial;

        if ((auth()->user()->vault_pin || ! auth()->user()->vault_pin_prompt_dismissed)
            && session($unlockKey, 0) < now()->timestamp) {
            return response()->noContent(401);
        }

        session([$unlockKey => now()->addSeconds(30)->timestamp]);
        return response()->noContent();
    }
}

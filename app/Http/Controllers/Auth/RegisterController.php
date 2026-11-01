<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'telefono'              => 'nullable|string|max:30',
        ]);

        $user = User::create($datos);
        Auth::login($user);

        return redirect()->route('dashboard.index')
            ->with('success', '¡Cuenta creada exitosamente! Bienvenido, ' . $user->name . '.');
    }
}

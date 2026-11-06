<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.verify-email', ['email' => $request->user()->email]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $validated = $request->validate(['code' => 'required|digits:6']);
        $user = $request->user();
        $expiresAt = $user->email_verification_code_expires_at;

        if (! $user->email_verification_code_hash || ! $expiresAt || $expiresAt->isPast()) {
            return back()->withErrors(['code' => 'El código venció o ya no es válido. Solicita uno nuevo.']);
        }

        if (! Hash::check($validated['code'], $user->email_verification_code_hash)) {
            return back()->withErrors(['code' => 'El código ingresado no es correcto.']);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code_hash' => null,
            'email_verification_code_expires_at' => null,
        ])->save();
        event(new Verified($user));

        $destination = ($user->vault_pin || $user->vault_pin_prompt_dismissed)
            ? route('dashboard.index')
            : route('security.pin.onboarding');

        return redirect($destination)
            ->with('success', 'Correo verificado correctamente.');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['email' => 'No se pudo enviar el código. Inténtalo de nuevo más tarde.']);
        }

        return back()->with('status', 'Enviamos un código nuevo. El código anterior dejó de ser válido.');
    }
}

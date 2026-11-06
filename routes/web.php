<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\HibpController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (sin autenticación)
|--------------------------------------------------------------------------
*/

// Landing Page pública de bienvenida
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return view('landing');
})->name('landing');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class,    'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);

    // Recuperación de contraseña
    Route::get('/forgot-password',          [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password',         [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}',   [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',          [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verify-code', [EmailVerificationController::class, 'verifyCode'])->middleware('throttle:10,1')->name('verification.code');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Logout
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('dashboard', DashboardController::class)->except(['index']);

    // Bóvedas (Categorías) - CRUD completo
    Route::post('/categorias/quick-store', [CategoriaController::class, 'quickStore'])->name('categorias.quick-store');
    Route::resource('categorias', CategoriaController::class);

    // Credenciales - CRUD completo con clave primaria personalizada
    Route::resource('credenciales', CredencialController::class)->parameters([
        'credenciales' => 'id_credencial',
    ]);
    Route::post('/credenciales/{id_credencial}/pin/recuperar', [CredencialController::class, 'setRecoveredPin'])->name('credenciales.pin-recovery.store');
    Route::post('/credenciales/{id_credencial}/pin/continuar', [CredencialController::class, 'continueWithoutPin'])->name('credenciales.pin-recovery.skip');
    Route::post('/credenciales/{id_credencial}/bloquear', [CredencialController::class, 'lock'])->name('credenciales.lock');
    Route::post('/credenciales/{id_credencial}/actividad', [CredencialController::class, 'keepAlive'])->name('credenciales.keep-alive');

    // Perfil de usuario activo
    Route::get('/perfil',            [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil',            [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password',   [PerfilController::class, 'updatePassword'])->name('perfil.password');
    Route::put('/perfil/pin', [PerfilController::class, 'updatePin'])->name('perfil.pin');
    Route::get('/perfil/avatar', [PerfilController::class, 'avatar'])->name('perfil.avatar');
    Route::post('/perfil/avatar', [PerfilController::class, 'updateAvatar'])->name('perfil.avatar.update');
    Route::delete('/perfil/avatar', [PerfilController::class, 'removeAvatar'])->name('perfil.avatar.remove');
    Route::get('/seguridad/pin/inicial', [PerfilController::class, 'pinOnboarding'])->name('security.pin.onboarding');
    Route::post('/seguridad/pin/inicial', [PerfilController::class, 'savePinOnboarding'])->name('security.pin.onboarding.store');

    // Herramienta: Verificador de contraseñas filtradas (API de tercero)
    Route::get('/herramientas/verificar-password',    [HibpController::class, 'index'])->name('hibp.index');
    Route::post('/herramientas/verificar-password',   [HibpController::class, 'verificar'])->name('hibp.verificar');

    // Informe PDF descargable
    Route::match(['get', 'post'], '/informe/pdf', [PdfController::class, 'informe'])->name('informe.pdf');
    Route::post('/credenciales/{id_credencial}/revelar', [CredencialController::class, 'reveal'])->name('credenciales.reveal');
});

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

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('dashboard', DashboardController::class)->except(['index']);

    // Bóvedas (Categorías) - CRUD completo
    Route::resource('categorias', CategoriaController::class);

    // Credenciales - CRUD completo con clave primaria personalizada
    Route::resource('credenciales', CredencialController::class)->parameters([
        'credenciales' => 'id_credencial',
    ]);

    // Perfil de usuario activo
    Route::get('/perfil',            [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil',            [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password',   [PerfilController::class, 'updatePassword'])->name('perfil.password');

    // Herramienta: Verificador de contraseñas filtradas (API de tercero)
    Route::get('/herramientas/verificar-password',    [HibpController::class, 'index'])->name('hibp.index');
    Route::post('/herramientas/verificar-password',   [HibpController::class, 'verificar'])->name('hibp.verificar');

    // Informe PDF descargable
    Route::get('/informe/pdf', [PdfController::class, 'informe'])->name('informe.pdf');
});

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\SecureVaultResetPassword;
use App\Notifications\SecureVaultVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'telefono',
        'vault_pin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'vault_pin',
        'email_verification_code_hash',
        'email_verification_code_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Devuelve la URL del avatar o un avatar generado automáticamente.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return route('perfil.avatar', ['v' => md5($this->avatar)]);
        }
        // Avatar generado por iniciales vía UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=ffffff&size=128';
    }

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function credenciales(): HasMany
    {
        return $this->hasMany(Credencial::class);
    }

    public function sendEmailVerificationNotification()
    {
        $code = (string) random_int(100000, 999999);
        $this->forceFill([
            'email_verification_code_hash' => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(15),
        ])->save();

        $this->notify(new SecureVaultVerifyEmail($code));
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new SecureVaultResetPassword($token));
    }
}

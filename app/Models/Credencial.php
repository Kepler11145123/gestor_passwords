<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Credencial extends Model
{
    use HasFactory;

    protected $table = 'credenciales';

    /**
     * Clave primaria personalizada de tipo string no autoincremental.
     */
    protected $primaryKey = 'id_credencial';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_credencial',
        'categoria_id',
        'titulo',
        'usuario',
        'password',
        'url',
        'notas',
    ];

    /**
     * Evento del modelo: si no se provee un identificador string personalizado al crear,
     * se genera uno único alfanumérico limpio.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = 'CRD-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Relación: Una credencial pertenece a una categoría.
     * Llave foránea 'categoria_id', llave local 'id' en categorias.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    /**
     * Principio de Responsabilidad Única (SRP):
     * La lógica pesada de procesamiento y encriptación nativa reside en el Modelo.
     * Cifra la contraseña utilizando Crypt::encryptString() antes de la persistencia.
     */
    public static function registrarCredencial(array $datos): self
    {
        if (!empty($datos['password'])) {
            $datos['password'] = Crypt::encryptString($datos['password']);
        }

        return self::create($datos);
    }

    /**
     * Principio de Responsabilidad Única (SRP):
     * Actualiza los datos de la credencial. Re-encripta la contraseña de forma nativa
     * solo si el usuario ingresó un nuevo valor; si viene vacía, no altera la existente.
     */
    public function modificarCredencial(array $datos): bool
    {
        if (!empty($datos['password'])) {
            $datos['password'] = Crypt::encryptString($datos['password']);
        } else {
            unset($datos['password']);
        }

        return $this->update($datos);
    }

    /**
     * Accesor / helper para descifrar la contraseña de forma segura.
     */
    public function obtenerPasswordDesencriptada(): string
    {
        try {
            return Crypt::decryptString($this->password);
        } catch (\Exception $e) {
            return 'No se pudo desencriptar la contraseña';
        }
    }
}

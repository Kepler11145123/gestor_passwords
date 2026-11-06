<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Una categoría tiene muchas credenciales.
     * Llave foránea 'categoria_id' en la tabla credenciales, llave local 'id'.
     */
    public function credenciales(): HasMany
    {
        return $this->hasMany(Credencial::class, 'categoria_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tarea 5 - Principio de Responsabilidad Única (SRP):
     * Función en el Modelo que usa constructores de consulta (select, count, groupBy)
     * para calcular dinámicamente cuántas credenciales existen por cada categoría.
     * Cero datos quemados: abastece en tiempo real a Chart.js.
     */
    public static function obtenerConteoCredencialesPorCategoria(): Collection
    {
        return self::select('categorias.nombre')
            ->selectRaw('COUNT(credenciales.id_credencial) as total')
            ->leftJoin('credenciales', function ($join) {
                $join->on('categorias.id', '=', 'credenciales.categoria_id')
                    ->where('credenciales.user_id', auth()->id());
            })
            ->where('categorias.user_id', auth()->id())
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderBy('categorias.nombre', 'asc')
            ->get();
    }

    /**
     * Alias de compatibilidad para estadísticas generales.
     */
    public static function obtenerEstadisticasParaGrafico(): Collection
    {
        return self::obtenerConteoCredencialesPorCategoria();
    }
}

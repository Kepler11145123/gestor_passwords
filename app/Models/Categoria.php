<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
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
            ->leftJoin('credenciales', 'categorias.id', '=', 'credenciales.categoria_id')
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

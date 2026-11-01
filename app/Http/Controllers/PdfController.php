<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Credencial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    /**
     * Genera y descarga un informe PDF de seguridad.
     * Usa DomPDF para convertir el HTML del informe en un PDF descargable.
     */
    public function informe(): Response
    {
        $estadisticas = Categoria::obtenerConteoCredencialesPorCategoria();
        $totalCategorias = Categoria::count();
        $totalCredenciales = Credencial::count();
        $ultimasCredenciales = Credencial::with('categoria')->latest()->take(20)->get();
        $fecha = now()->format('d/m/Y H:i');

        // Genera el contenido HTML del informe
        $html = view('pdf.informe', compact(
            'estadisticas',
            'totalCategorias',
            'totalCredenciales',
            'ultimasCredenciales',
            'fecha'
        ))->render();

        // Si DomPDF está instalado lo usamos, si no enviamos HTML imprimible
        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadHTML($html);

            return $pdf->download('informe-seguridad-'.now()->format('Y-m-d').'.pdf');
        }

        // Fallback: HTML imprimible con cabecera que indica al navegador impresión
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Print-On-Load' => 'true',
        ]);
    }
}

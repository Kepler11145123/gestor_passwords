<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Credencial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class PdfController extends Controller
{
    /**
     * Genera y descarga un informe PDF de seguridad.
     * Usa DomPDF para convertir el HTML del informe en un PDF descargable.
     */
    public function informe(Request $request): Response|RedirectResponse|\Illuminate\Contracts\View\View
    {
        if ($request->isMethod('post')) {
            $request->validate(['password' => 'required|string']);
            if (! Hash::check($request->input('password'), $request->user()->password)) {
                return back()->withErrors(['password' => 'La contraseña de acceso no es correcta.']);
            }
            $request->session()->put('pdf.confirmed_until', now()->addMinutes(5)->timestamp);
            return view('pdf.confirmar');
        }
        if (! $request->session()->has('pdf.confirmed_until') || $request->session()->get('pdf.confirmed_until') < now()->timestamp) {
            return view('pdf.autorizar');
        }
        if (! $request->boolean('download')) {
            return view('pdf.confirmar');
        }
        $request->session()->forget('pdf.confirmed_until');
        $estadisticas = auth()->user()->categorias()->leftJoin('credenciales', function ($join) {
            $join->on('categorias.id', '=', 'credenciales.categoria_id')->where('credenciales.user_id', auth()->id());
        })->select('categorias.nombre')->selectRaw('COUNT(credenciales.id_credencial) as total')
            ->groupBy('categorias.id', 'categorias.nombre')->get();
        $totalCategorias = auth()->user()->categorias()->count();
        $totalCredenciales = auth()->user()->credenciales()->count();
        $ultimasCredenciales = auth()->user()->credenciales()->with('categoria')->latest()->take(20)->get();
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

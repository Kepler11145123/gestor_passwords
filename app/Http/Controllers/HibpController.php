<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HibpController extends Controller
{
    /**
     * Muestra la vista de verificación de contraseñas filtradas.
     */
    public function index(): View
    {
        return view('herramientas.hibp');
    }

    /**
     * Consulta la API de HaveIBeenPwned para verificar contraseñas comprometidas.
     */
    public function verificar(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string|min:1',
        ]);

        $password  = $request->input('password');
        $sha1Hash  = strtoupper(sha1($password));
        $prefix    = substr($sha1Hash, 0, 5);
        $suffix    = substr($sha1Hash, 5);

        // Llamada a la API pública de HIBP (modelo de anonimato k-Anonymity)
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => "User-Agent: GestorContrasenas-Laravel\r\n",
                'timeout' => 5,
            ]
        ]);

        $respuesta = @file_get_contents(
            'https://api.pwnedpasswords.com/range/' . $prefix,
            false,
            $context
        );

        if ($respuesta === false) {
            return response()->json([
                'status'  => 'error',
                'mensaje' => 'No se pudo conectar con la API de HaveIBeenPwned. Verifica tu conexión.',
            ], 503);
        }

        // Buscar si nuestro sufijo aparece en la respuesta
        $lineas      = explode("\n", $respuesta);
        $comprometida = false;
        $veces        = 0;

        foreach ($lineas as $linea) {
            $partes = explode(':', trim($linea));
            if (isset($partes[0]) && strtoupper($partes[0]) === $suffix) {
                $comprometida = true;
                $veces        = (int) ($partes[1] ?? 0);
                break;
            }
        }

        return response()->json([
            'status'       => 'ok',
            'comprometida' => $comprometida,
            'veces'        => $veces,
            'mensaje'      => $comprometida
                ? "Esta contraseña ha aparecido en {$veces} filtraciones conocidas. Cámbiala de inmediato."
                : 'Esta contraseña no fue encontrada en filtraciones conocidas.',
        ]);
    }
}

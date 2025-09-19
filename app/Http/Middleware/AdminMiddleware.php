<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene la clase 'administrador'
        if ($user->clase !== 'administrador') {
            // Si no es administrador, denegar acceso
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso denegado. Se requieren permisos de administrador.',
                    'error' => 'Unauthorized'
                ], 403);
            }

            // Para requests web, redireccionar con mensaje de error
            return redirect()->back()->with('error', 'No tienes permisos de administrador para acceder a esta sección.');
        }

        // Si es administrador, continuar con la petición
        return $next($request);
    }
}
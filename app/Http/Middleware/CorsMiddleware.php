<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Events\ModifyResponse;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $response = $next($request);

    // Verificar si $next($request) devolvió una respuesta válida
    if ($response instanceof \Illuminate\Http\Response) {
        // Permitir solicitudes desde cualquier origen
        $response = $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        
        // Emitir evento solo si la respuesta es válida
        event(new ModifyResponse($response));
    }

    return $response;
}
}

<?php

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApplicationKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-Application-Key');

        // Verifique se a chave da aplicação está na lista de chaves autorizadas

        if (!Application::query()->where("key","=",$apiKey)->exists()){
            return response()->json(['error' => 'Chave de aplicação inválida.'], 401);
        }

        return $next($request);
    }
}

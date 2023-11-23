<?php

namespace App\Http\Middleware;

use App\Models\Application;
use App\Models\ApplicationsRequestLog;
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
        $application = Application::query()->where("key","=",$apiKey)->first();

        if (is_null($application)){
            return response()->json(['error' => 'Chave de aplicação inválida.'], 401);
        }
        ApplicationsRequestLog::create([
            'method' => $request->method(),
            'path' => $request->path(),
            'query_parameters' => json_encode($request->query()),
            'headers' => json_encode($request->headers->all()),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            "application_id"=>$application->id
        ]);


        return $next($request);
    }
}

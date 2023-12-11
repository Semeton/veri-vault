<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestApiKey = $request->header('API-KEY');

        $apiKeyExits = $this->verifyApiKey($requestApiKey);
        
        if (!$apiKeyExits) {
            return response('Unauthorized.', 401);
        }
        
        return $next($request);
    }

    public function verifyApiKey($apiKey): ApiKey
    {
        $apiKeyModel = ApiKey::where('key', $apiKey)->first();

        return $apiKeyModel;
    }
}
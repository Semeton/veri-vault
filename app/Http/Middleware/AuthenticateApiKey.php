<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): ?Response
    {
        $requestApiKey = $request->header('API-KEY');

        $apiKeyExits = $this->verifyApiKey($requestApiKey);
        
        if (!$apiKeyExits) {
            return response()->json(['Unauthorized'], 401);
        }
        
        return $next($request);
    }

    public function verifyApiKey($apiKey): ?ApiKey
    {
        $apiKeyModel = DB::table('api_keys')->where('api_key', $apiKey)->first();

        return $apiKeyModel;
    }
}
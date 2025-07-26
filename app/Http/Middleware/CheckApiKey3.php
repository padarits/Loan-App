<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckApiKey3
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pārbaudi, vai 'X-API-KEY' header ir iestatīts un vai tas atbilst iestatītajam API atslēgas vērtībai
        $apiKey = $request->header('Authorization');

        if (Str::substr($apiKey, 7) === config('services.Company.SetApiSecretPasswordChatGpt')) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Invalid API key. (3)'
        ], 401);
    }
}


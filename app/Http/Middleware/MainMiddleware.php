<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        // Pārbaudi, vai lietotājs ir autentificējies un viņa loma ir kāda no atļautajām lomām
        if (Auth::check() && $user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Ja lietotājam nav piekļuves, pāradresē uz sākumlapu vai atgriez pieejas atteikumu
        return redirect('/')->with('error', 'You do not have sufficient permissions to access this page.');
    }
}

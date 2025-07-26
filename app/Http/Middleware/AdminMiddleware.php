<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        // Pārbaudi, vai lietotājs ir autentificējies un viņa loma ir kāda no atļautajām lomām
        if (Auth::check() && $user->hasRole('admin')) {
            return $next($request);
        }

        // Ja lietotājam nav piekļuves, pāradresē uz sākumlapu vai atgriez pieejas atteikumu
        return redirect('/')->with('error', 'You do not have sufficient permissions to access this page.');
    }
}



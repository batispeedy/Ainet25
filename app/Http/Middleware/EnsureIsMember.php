<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsMember
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->type !== 'member') {
            return redirect()->route('membership.show')
                ->with('error', 'Tens de pagar a quota para aceder a esta funcionalidade.');
        }

        return $next($request);
    }
}

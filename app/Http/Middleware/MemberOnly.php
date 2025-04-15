<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberOnly
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type !== 'member') {
            return redirect()->route('membership.show')->with('error', 'Tens de pagar a quota de adesão para comprar.');
        }

        return $next($request);
    }
}

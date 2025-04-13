<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMembershipFeeIsPaid
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->type !== 'member') {
            return redirect()->route('store.index')->with('error', 'Apenas membros do clube têm acesso.');
        }

        if (!$user->membership_paid) {
            return redirect()->route('membership.show')->with('error', 'Precisas de pagar a quota para aceder.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMembershipFeePaid
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || ! $user->has_paid_membership_fee) {
            return redirect()->route('membership.show');
        }

        return $next($request);
    }
}

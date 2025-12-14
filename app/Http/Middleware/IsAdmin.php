<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roleId = Auth::user()->role->first()->idrole ?? null;
        if ($roleId !== 1) {
            abort(403, 'Dilarang masuk, bukan area admin!');
        }

        return $next($request);
    }
}

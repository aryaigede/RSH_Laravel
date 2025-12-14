<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsDokter
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roleId = Auth::user()->role->first()->idrole ?? null;
        if ($roleId !== 2) {
            abort(403, 'Dilarang masuk, bukan area dokter!');
        }

        return $next($request);
    }
}

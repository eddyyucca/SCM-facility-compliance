<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $menuKey): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! Auth::user()->canAccessMenu($menuKey)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}

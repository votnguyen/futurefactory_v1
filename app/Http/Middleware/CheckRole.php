<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role; // Cruciale import!

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->roles()->whereIn('name', $roles)->exists()) {
            abort(403);
        }
        return $next($request);
    }
}
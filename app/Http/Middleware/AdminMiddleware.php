<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || ($user->role ?? null) !== 'admin') {
            abort(403, 'Anda tidak memiliki akses admin.');
        }
        return $next($request);
    }
}
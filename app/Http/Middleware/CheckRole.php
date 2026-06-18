<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();
        if (!$user)                           abort(401);
        if (!in_array($user->role, $roles))   abort(403);
        if ($user->status !== 'active')       abort(403, 'Account suspended');
        return $next($request);
    }
}

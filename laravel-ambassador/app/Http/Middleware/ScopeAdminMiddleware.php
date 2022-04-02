<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App\Services\UserService;

class ScopeAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = (new UserService)->getRequest('get', 'scope/admin');
        
        if (!$response->ok()) {
            abort(401, 'unauthorized');
        }

        return $next($request);
    }
}

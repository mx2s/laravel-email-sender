<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return JsonResponse|mixed
     */
    public function handle($request, $next, ...$guards) {
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::where('api_token', $request->get('api_token'))->first() == null) {
            return response()->json([
                "message" => "invalid API token"
            ], 401);
        }

        return $next($request);
    }
}

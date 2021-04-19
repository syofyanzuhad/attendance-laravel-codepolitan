<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if (! auth()->user()->is_admin) {
            auth()->logout();
            return redirect()->route('login')->withErrors('Anda tidak memiliki ijin !');
        }
        return $next($request);
    }
}

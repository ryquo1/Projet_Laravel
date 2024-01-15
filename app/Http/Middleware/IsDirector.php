<?php

namespace App\Http\Middleware;

use App\Models\web\AcnMember;
use Closure;

class IsDirector
{
    /**
     * Handle an incoming request for determinate if the user is a director or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $isDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);

        if (!$isDirector) {
            return redirect(route("welcome"));
        }

        return $next($request);
    }
}

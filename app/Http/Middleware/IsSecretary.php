<?php

namespace App\Http\Middleware;

use App\Models\web\AcnMember;
use Closure;

class IsSecretary
{
    /**
     * Handle an incoming request for determinate if the user is a secretary or not
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $isSecretary = AcnMember::isUserSecretary(auth()->user()->MEM_NUM_MEMBER);

        if (!$isSecretary) {
            return redirect(route("welcome"));
        }

        return $next($request);
    }
}

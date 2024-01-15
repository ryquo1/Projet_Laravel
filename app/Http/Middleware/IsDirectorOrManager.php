<?php

namespace App\Http\Middleware;

use App\Models\web\AcnMember;
use Closure;

class IsDirectorOrManager
{
    /**
     * Handle an incoming request for determinate if the user is a director or a manager or neither of the two
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $isDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);
        $isManager = AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER);

        if (!$isDirector && !$isManager) {
            return redirect(route("welcome"));
        }

        return $next($request);
    }










}

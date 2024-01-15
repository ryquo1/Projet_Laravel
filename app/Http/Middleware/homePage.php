<?php

namespace App\Http\Middleware;

use App\Models\web\AcnMember;
use Closure;

class HomePage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $isSecretary = AcnMember::isUserSecretary(auth()->user()->MEM_NUM_MEMBER);
        $isDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);
        $isManager = AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER);

        if ($isSecretary) {
            return redirect(route('members'));
        }else if($isDirector){
            return redirect(route('myDirectorDives'));
        }else if($isManager){
            return redirect(route('diveCreation'));
        }
            return $next($request);
    }
}

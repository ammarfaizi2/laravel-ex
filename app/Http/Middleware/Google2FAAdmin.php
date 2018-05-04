<?php

namespace App\Http\Middleware;


use Confide;
use Closure;
use App\User;
use PragmaRX\Google2FALaravel\Middleware;

class Google2FAAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Confide::user();
        if (isset($user->google2fa_secret) and $user->google2fa_secret && session()->get("google2fa") === null) {
        	if (! User::find($user->id)->hasRole('admin')) {
        		abort(404);
        	}
            print view("2fa_lock");
            exit();
        }
        return $next($request);
    }
}

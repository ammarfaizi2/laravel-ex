<?php

namespace App\Http\Middleware;

use Confide;
use Closure;
use PragmaRX\Google2FALaravel\Middleware;

class Google2FA
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
            print view("2fa_lock");
            exit();
        }
        return $next($request);
    }
}

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
        $u = Confide::user();
        if ($u !== null && $u->google2fa_secret !== null) {
            $curUrl = url()->current();
            if ($curUrl !== route('2fa')) {
                session(['2fa_redirect' => $curUrl]);
            }
            return (new Middleware())->handle($request, $next);
        } else {
            return $next($request);
        }
    }
}

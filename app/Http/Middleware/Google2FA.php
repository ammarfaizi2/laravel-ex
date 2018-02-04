<?php

namespace App\Http\Middleware;

use Confide;
use Closure;
use Redirect;
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
            $currentUrl = url()->current();
            if ($currentUrl !== route('2fa')) {
                session(
                    [
                        '2fa_previous_url' => $currentUrl
                    ]
                );
            } else {
                $prev = session()->get('2fa_previous_url');
                if ($currentUrl === route('2fa') && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                    return Redirect::to($prev);
                }
            }
            return (new Middleware())->handle($request, $next);
        } else {
            return $next($request);
        }
    }
}

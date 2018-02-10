<?php

namespace App\Http\Middleware;

use Closure;
use Confide;
use Redirect;

class BrowserIdentificator
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
        if ($user) {
            $s = session()->get("_identificator");
            if ($s["user_agent"] !== $_SERVER["HTTP_USER_AGENT"]) {
                Confide::logout();
                session([
                    "google2fa" => null,
                    "admin_page" => null,
                    "_identificator" => null
                ]);
                return Redirect::to(route("user.login"))->with("error", "Browser has been changed");
            }
        }
        return $next($request);
    }
}

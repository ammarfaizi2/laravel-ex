<?php

namespace App;

use DB;
use Confide;
use Google2FA;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function google2fa($code)
    {
        $user = Confide::user();
        if (! $user && isset($_POST["user"])) {
            $user = \DB::table("users")->select("google2fa_secret")->where("username", "=", $_POST["user"])->first();
            if (! $user) {
                return false;
            }
        }
        if ($user->google2fa_secret) {
            return Google2Fa::verifyKey(
                $user->google2fa_secret,
                $code,
                1,
                null, // $timestamp
                "__not_set__"
            );
        }
    }

    public static function _hasRole($role)
    {
        $user = Confide::user();
        if ($user) {
            if ($user = \DB::table("users_roles")
            ->select(["users_roles.role_id", "roles.name"])
            ->join("roles", "roles.id", "=", "users_roles.role_id", "inner")
            ->where("users_roles.user_id", "=", $user->id)
            ->first()) {
                return strtolower($user->name) === strtolower($role);
            }
        }
        return false;
    }
}

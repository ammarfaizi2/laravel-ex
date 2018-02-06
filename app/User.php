<?php

namespace App;

use Confide;
use Google2FA;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
}

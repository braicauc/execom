<?php

namespace App;

use Illuminate\Support\Facades\Redis;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'username', 'password', 'email', 'facebook_id', 'avatar', 'gender', 'telefon', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    /**
     * @param $authUser - authentificated user
     * set the key into a session var personalKey   
     * @return string cu uniqid
     */
    public function setPersonalKey($authUser) {

        $uid = uniqid();

        session(['personalKey' => $uid]);

        $keys = @explode(",",Redis::get("user:keys:".$authUser->id));

        if ( !empty($keys) ) {
            $keys = trim(implode(",",array_slice(array_prepend($keys,$uid),0,3)),",");
        } else {
            $keys = $uid;
        }

        Redis::set("user:keys:".$authUser->id,$keys);

        return $uid;
    }


    /**
     * Set user instance to Redis databese under the key user:det:user_id
     * This instance is used in node into node/class/User.js
     * @param $authUser
     */
    public function setRedisUser($authUser) {
        Redis::set("user:det:".$authUser->id,$authUser);
    }




}

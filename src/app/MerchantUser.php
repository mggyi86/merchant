<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable
{
     
     protected $table = 'merchant_users';
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'username', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    // ];

    public function getAuthIdentifier()
    {
        return $this->id.$this->email;
    }

    public function getGuardName()
    {
        return 'merchantuser';
    }
}

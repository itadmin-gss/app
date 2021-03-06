<?php

namespace App;

class ResetPassword extends BaseTenantModel
{

    protected $table = 'reset_passwords';
    protected $fillable = ['id','email','password','created_at','updated_at'];

    public static function savePassword($data)
    {
        $passwords = ResetPassword::create($data);
        return $passwords->id;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
    protected $fillable = ['user_id', 'token'];
    
    //Add token to database
    public static function addToken($id, $token)
    {
        $save = Tokens::create(['user_id' => $id, 'token' => $token]);
        return $save->id;
    }
}

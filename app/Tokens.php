<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
    //Add token to database
    public function addToken($id, $token)
    {
        $save = Tokens::create(['user_id' => $id, 'token' => $token]);
        return $save->id;
    }
}

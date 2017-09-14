<?php

namespace App;



class Registration extends BaseTenantModel
{

    

    protected $table = 'user';
       
    public static function userTypeId($id)
    {
      
        $user_detail = self::find($id);
        $id = $user_detail['type_id'];
        return $id;
    }
}

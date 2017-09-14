<?php

namespace App;




class RoleDetail extends BaseTenantModel
{



    protected $table = 'user_role_details';

    protected $fillable = ['id','role_id','role_function_id', 'add', 'edit', 'delete','view', 'status'];

    // Defining Function to get User Profile Status. ---- Start





    public function roleFunctions()
    {

        return $this->belongsTo('App\RoleFunction', 'role_function_id');

        //return user type
    }



    public function userRole()
    {

        return $this->belongsTo('App\UserRole', 'role_id');
    }



    public static function addRoleDetail($data)
    {

        $role_detail = self::create($data);

        return ($role_detail) ? $role_detail : false;
    }

    // Defining Function to get User Profile Status. ---- End
}

<?php

use Illuminate\Database\Eloquent\SoftDeletes;

class RoleFunction extends BaseTenantModel
{



    use SoftDeletes;

    protected $table = 'role_functions';



    // Defining Function to get User Profile Status. ---- Start



    public static function listRoleFunctions()
    {

        $role_function = self::all();

        return $role_function;
    }

    

    public static function getRoleFunction($id)
    {

        $role_function = self::find($id);

        return $role_function;
    }

    

    public function roleDetails()
    {

        return $this->hasMany('RoleDetail', 'role_function_id');

        //return user type
    }

    

    public function accessFunction()
    {

        return $this->belongsTo('AccessFunction', 'access_function_id');

        //return user type
    }

    



    // Defining Function to get User Profile Status. ---- End
}

<?php

namespace App;

class AccessFunction extends BaseTenantModel
{

    protected $table = 'access_functions';

    // Defining Function to get User Profile Status. ---- Start

    
    public function roleFunctions()
    {
        return $this->hasMany(\App\RoleFunction::class, 'access_function_id');
        //return user type
    }
    

    // Defining Function to get User Profile Status. ---- End
}

<?php



class UserRole extends BaseTenantModel
{



    protected $table = 'user_roles';

    protected $fillable = ['id', 'role_name', 'description', 'status'];



    public static function addRoles($data)
    {



        $UserRoles = UserRoles::create($data);

        return ($UserRoles) ? true : false;
    }



    public static function listRoles()
    {



        $UserRoles = self::all();

        return $UserRoles;
    }



    public function user()
    {

        return $this->hasMany('User', 'user_role_id');
    }

    

    public function roleDetails()
    {

        return $this->hasMany('RoleDetail', 'role_id');
    }

    

    public static function updateRole($role_data, $role_id)
    {

        $role = self::find($role_id);

        $role->role_name = $role_data['role_name'];

        $role->description = $role_data['description'];

        $role->status = $role_data['status'];

        $save=$role->save();

        return ($save) ? true : false;
    }

    

    public static function updateStatus($role_status, $role_id)
    {

        $role = self::find($role_id);

        $role->status = $role_data['status'];

        $save=$role->save();

        return ($save) ? true : false;
    }

    

    public static function getAllRoles()
    {

        $userRoles = self::all();

        return $userRoles;
    }

    

    public function getTable()
    {

        return $this->table;
    }
}

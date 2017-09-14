<?php



class UserType extends BaseTenantModel
{



    protected $table = 'user_types';



    // Defining Function to get User Profile Status. ---- Start

    public static function getUserTypeByID($id)
    {

        $user_type = self::find($id);

        return  $user_type->title; //return user type
    }



    // Defining Function to get User Profile Status. ---- End



    public function user()
    {

        return $this->belongsTo('Users');
    }



    public static function getUserTypeIdByTitle($title)
    {

        $type_id = self::whereRaw('title = ?', [$title])->get(['id'])->first();



        return $type_id->id;
    }
}

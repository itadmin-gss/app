<?php







class State extends BaseTenantModel
{







    /**



     * The database table used by the model.



     *



     * @var string



     */



    protected $table = 'states';







    /**



     * The attributes excluded from the model's JSON form.



     *



     * @var array



     */



    



    public function city()
    {



        return $this->hasMany('City', 'state_id');
    }



  



    public static function getAllStates()
    {



        $states = self::all();



        return $states;
    }



    // func to get state via id by shm

    public static function getStateByID($id)
    {

        $state = self::find($id);

        // return  $state->name; // return state name

        return $state['name'];
    }
}

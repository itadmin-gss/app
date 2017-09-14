<?php



class City extends BaseTenantModel
{



    /**

     * The database table used by the model.

     *

     * @var string

     */

    protected $table = 'cities';



    /**

     * The attributes excluded from the model's JSON form.

     *

     * @var array

     */

    



    public function state()
    {

        return $this->belongsTo('State', 'state_id');
    }

    

    public function user()
    {

        return $this->hasMany('User', 'city_id');
    }

    

    public static function getCitiesByStateId($id)
    {

        $cities = self::whereRaw('state_id = ?', [$id])->orderBy('name', 'asc')->get(['id','name']);

        return $cities;
    }

    public static function getAllCities()
    {

        $cities = self::all();

         return $cities;
    }



    public static function updateCity($data, $id)
    {

      // $save = City::find($id)->update($data);

        $city = self::find($id);



        $city->name = $data['name'];



        $city->state_id = $data['state_id'];



        $city->status = 1;



        $save=$city->save();



        return $save;
    }
}

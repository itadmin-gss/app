<?php

namespace App;

class ServiceImageBid extends BaseTenantModel
{



    /**

     * The database table used by the model.

     *

     * @var string

     */

    protected $table = 'service_images_bids';

    protected $fillable = ['id', 'requested_id', 'image_name', 'status', 'image_type'];



    /**

     * The attributes excluded from the model's JSON form.

     *

     * @var array

     */

    public function requestedService()
    {

        return $this->belongsTo('App\RquestedService', 'requested_id');
    }



    public static function addServiceImage($data)
    {

        $add_service = self::create($data);

        return ($add_service) ? true : false;
    }
}

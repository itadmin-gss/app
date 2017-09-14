<?php

class Image extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';
    protected $fillable = array('id', 'requested_id', 'image_name', 'type');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static function addImage($data)
    {
    }
}

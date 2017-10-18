<?php

namespace App;

class AdditionalServiceItemImage extends BaseTenantModel
{

    protected $table = 'additional_service_item_images';

    protected $fillable = ['id', 'additional_service_id', 'type', 'address', 'created_at', 'updated_at'];

    public static function createImage($data)
    {
        
        $image = self::create($data);

        return $image;
    }
}

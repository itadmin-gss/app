<?php

namespace App;

class ServiceCategory extends BaseTenantModel
{

    protected $table = 'service_categories';
    protected $fillable = ['id','title','created_at','updated_at'];

    public static function getAllServicesCategories()
    {
        $service = self::get();
        return $service; //return user type
    }
}

<?php

namespace App;


class ServiceFieldDetail extends BaseTenantModel
{
    
    protected $table = 'service_field_details';

    protected $fillable = ['id', 'fieldname', 'service_id', 'field_type', 'field_values'];

    public static function add($data)
    {
        $data['status'] = 1;
        $save = self::create($data);
        return ($save) ? $save : false;
    }
    public static function getServiceFieldById($id)
    {
        $service = self::where('service_id', '=', $id)
                    ->select('fieldname', 'service_id', 'field_type', 'field_values')
                    ->get();
        return $service;
    }
    public static function updating($data)
    {
    }
}

<?php

class AdditionalServiceItem extends BaseTenantModel
{

    protected $table = 'additional_service_items';

    protected $fillable = array('id','title','description','rate','customer_price','admin_quantity','quantity','created_at','updated_at','order_id','vendor_id');

    public static function Add_Items($data)
    {

        $Items = AdditionalServiceItem::create($data);
    
        return $Items->id;
    }
}

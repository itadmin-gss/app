<?php

class OrderCustomData extends Eloquent
{

    protected $table = 'order_custom_data';
    protected $fillable = ['id', 'order_id', 'customers_notes', 'vendors_notes', 'notes_for_vendors', 'customer_price', 'vendors_price','admin_quantity','quantity', 'created_at', 'updated_at'];

    public static function createCustomData($data)
    {
        $info = self::create($data);
        return $info;
    }
}

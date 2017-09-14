<?php

class OrderDetail extends BaseTenantModel
{

    protected $table = 'order_details';
    protected $fillable = ['id', 'order_id', 'service_id', 'order_date', 'status', 'created_at', 'updated_at','requested_service_id'];

    public function order()
    {
        return $this->belongsTo('Order', 'order_id');
    }
    
    public function orderImage()
    {
        return $this->hasMany('OrderImage', 'order_details_id');
    }
    
    public function requestedService()
    {
        return $this->belongsTo('RequestedService', 'requested_service_id');
    }

    
    public static function addOrderDetails($data)
    {
        $order_details = self::create($data);
    }
}

<?php

namespace App;

class OrderDetail extends BaseTenantModel
{

    protected $table = 'order_details';
    protected $fillable = ['id', 'order_id', 'service_id', 'order_date', 'status', 'created_at', 'updated_at','requested_service_id'];

    public function order()
    {
        return $this->belongsTo(\App\Order::class, 'order_id');
    }
    
    public function orderImage()
    {
        return $this->hasMany(\App\OrderImage::class, 'order_details_id');
    }
    
    public function requestedService()
    {
        return $this->belongsTo(\App\RequestedService::class, 'requested_service_id');
    }

    
    public static function addOrderDetails($data)
    {
        $order_details = self::create($data);
    }
}

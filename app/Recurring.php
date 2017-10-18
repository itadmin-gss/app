<?php

namespace App;

class Recurring extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recurrings';

    protected $fillable = ['id', 'request_service_id', 'start_date', 'end_date', 'duration', 'vendor_id','status', 'assignment_type'];

    
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'vendor_id');
    }
    public function requestedService()
    {
        return $this->belongsTo(\App\RequestedService::class, 'request_service_id');
    }
}

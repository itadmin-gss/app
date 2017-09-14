<?php

namespace App;

class EmergencyRequest extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emergency_requests';
    protected $fillable = ['id', 'request_id', 'customer_id', 'status'];

    public function emergencyRequestDetail()
    {
        return $this->hasMany(\App\EmergencyRequestDetail::class, 'emergency_request_id');
    }
}

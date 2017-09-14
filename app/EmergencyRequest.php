<?php

class EmergencyRequest extends BaseTenantModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emergency_requests';
    protected $fillable = array('id', 'request_id', 'customer_id', 'status');

     public function emergencyRequestDetail() {
        return $this->hasMany('EmergencyRequestDetail', 'emergency_request_id');
    }
}

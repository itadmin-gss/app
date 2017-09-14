<?php

class EmergencyRequestDetail extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emergency_request_details';
    protected $fillable = array('id', 'emergency_request_id', 'request_id','vendor_id', 'distance','status');
}

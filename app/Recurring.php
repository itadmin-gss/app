<?php

class Recurring extends BaseTenantModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
  protected $table = 'recurrings';

  protected $fillable = array('id', 'request_service_id', 'start_date', 'end_date', 'duration', 'vendor_id','status', 'assignment_type');

    
    public function user() {
        return $this->belongsTo('User','vendor_id');
    }
    public function requestedService() {
        return $this->belongsTo('RequestedService','request_service_id');
    }
}
<?php

class RequestedService extends BaseTenantModel
{



    protected $table = 'requested_services';
    protected $fillable = array('id', 'request_id', 'service_id', 'status', 'created_at', 'updated_at', 'required_date', 'required_time', 'service_men', 'service_note','public_notes', 'verified_vacancy', 'cash_for_keys', 'cash_for_keys_trash_out', 'trash_size', 'storage_shed', 'lot_size','set_prinkler_system_type','install_temporary_system_type',   'carpet_service_type',  'pool_service_type',  'boarding_type','spruce_up_type','constable_information_type',
    'remove_carpe_type',
    'remove_blinds_type',
    'remove_appliances_type',
    'due_date',
    'recurring',
    'recurring_start_date',
    'recurring_end_date',
    'duration',
    'bidding_prince','quantity','customer_price','emergency','customer_note','vendor_note');

    public function service()
    {
        return $this->belongsTo('Service', 'service_id');
    }

    public function asset()
    {
        return $this->belongsTo('Asset', 'asset_id');
    }

    public function assignRequest()
    {
        return $this->hasMany('AssignRequest', 'requested_service_id');
    }

    public function orderDetail()
    {
        return $this->hasMany('OrderDetail', 'requested_service_id');
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo('MaintenanceRequest', 'request_id');
    }

    public function serviceImages()
    {
        return $this->hasMany('ServiceImage', 'requested_id');
    }
    public static function addRequestedService($data)
    {
        $request_service = self::create($data);

        return ($request_service) ? true : false ;
    }
    public static function addAdditionalRequestedService($data)
    {
        $request_service = self::create($data);

        return $request_service->id;
    }
}

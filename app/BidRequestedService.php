<?php

namespace App;


class BidRequestedService extends BaseTenantModel
{



    protected $table = 'bid_requested_services';
    protected $fillable = ['id', 'request_id', 'maintenance_request_id' ,'service_id', 'status', 'created_at', 'updated_at', 'required_date', 'required_time', 'service_men', 'service_note', 'verified_vacancy', 'cash_for_keys', 'cash_for_keys_trash_out', 'trash_size', 'storage_shed', 'lot_size','biding_prince','customer_price'];

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

    public function asset()
    {
        return $this->belongsTo('App\Asset', 'asset_id');
    }

    
    public function maintenanceRequest()
    {
        return $this->belongsTo('App\MaintenanceRequest', 'maintenance_request_id');
    }

    public function serviceImages()
    {
        return $this->hasMany('App\BidServiceImage', 'requested_id');
    }
    public static function addRequestedService($data)
    {
        $request_service = self::create($data);

        return ($request_service) ? true : false ;
    }
}

<?php

namespace App;




class BidRequest extends BaseTenantModel
{







    protected $table = 'bid_requests';

    protected $fillable = ['id', 'vendor_id', 'asset_id', 'status', 'created_at', 'updated_at','order_id','customer_id','decline_notes','customer_notes','admin_notes','job_type'];



    public function user()
    {

        return $this->belongsTo('App\User', 'vendor_id');
    }



    public function customer()
    {

        return $this->belongsTo('App\User', 'customer_id');
    }





    public function asset()
    {

        return $this->belongsTo('App\Asset', 'asset_id');
    }



    public function assignRequest()
    {

        return $this->hasMany('App\AssignRequest', 'request_id');
    }



    public function requestedService()
    {

        return $this->hasMany('App\RequestedService', 'request_id');
    }



    public function order()
    {

        return $this->hasMany('App\Order', 'request_id');
    }



    public static function addMaintenanceRequest($data)
    {

        $maintenance = self::create($data);

        return ($maintenance) ? true : false;
    }



    public static function viewAllMaintenanceRequest()
    {

        $requests = self::all();

        return $requests;
    }



    public static function listMaintenanceRequestByCustomerId($customer_id, $take = 0)
    {



        if ($take == 0) {
            $requests = self::whereRaw('customer_id = ? and status = 1', [$customer_id])->get();
        } else {
            $requests = self::whereRaw('customer_id = ? and status = 1', [$customer_id])

                            ->skip(0)

                            ->take($take)->get();
        }

        return $requests;
    }



    public static function viewDetailByRequestId($id)
    {

        $request_detail = self::find($id);



        return $request_detail;
    }



    public static function getMaintenanceRequestByAssetId($asset_id = 1)
    {

        $data = self::where('asset_id', '=', $asset_id)->get();

        return $data;
    }
}

<?php

namespace App;

class MaintenanceBid extends BaseTenantModel
{



    protected $table = 'maintenance_bids';
    protected $fillable = ['id', 'customer_id', 'substitutor_id' ,'asset_id', 'status','emergency_request', 'admin_notes' ,'declined_notes','job_type','created_at', 'updated_at','declinebidnotes'];

    public function jobType()
    {
        return $this->belongsTo(\App\JobType::class, 'job_type');
    }
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'customer_id');
    }
    public function user2()
    {
        return $this->belongsTo(\App\User::class, 'substitutor_id');
    }

    public function asset()
    {
        return $this->belongsTo(\App\Asset::class, 'asset_id');
    }

    public function assignRequest()
    {
        return $this->hasMany(\App\AssignRequestBid::class, 'request_id');
    }
    public function invoiceRequest()
    {
        return $this->hasMany(\App\Invoice::class, 'request_id');
    }

    public function requestedService()
    {
        return $this->hasMany(\App\RequestedBid::class, 'request_id');
    }

    public function order()
    {
        return $this->hasMany(\App\Order::class, 'request_id');
    }

    public static function addMaintenanceRequest($data)
    {
        $maintenance = MaintenanceBid::create($data);
        return ($maintenance) ? true : false;
    }

    public static function viewAllMaintenanceRequest()
    {
        $requests = self::all();
        return $requests;
    }

    public static function listMaintenanceRequestByCustomerId2($customer_id, $take = 0)
    {

        if ($take == 0) {
            $requests = self::whereRaw('customer_id = ?', [$customer_id])->orderBy('id', 'desc')->get();
        } else {
            $requests = self::whereRaw('customer_id = ? and status = 1', [$customer_id])
                        ->skip(0)
                        ->take($take)->orderBy('id', 'desc')->get();
        }
            return $requests;
    }
    public static function listMaintenanceRequestByCustomerId($customer_id, $take = 0)
    {

        if ($take == 0) {
            $requests = self::whereRaw('customer_id = ? and status in (1,6,4,7,8)', [$customer_id])->orderBy('id', 'desc')->get();
        } else {
            $requests = self::whereRaw('customer_id = ? and status in (1,6,4,7,8)', [$customer_id])
                            ->skip(0)
                            ->take($take)->orderBy('id', 'desc')->get();
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

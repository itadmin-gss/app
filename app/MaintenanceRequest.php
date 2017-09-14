<?php

class MaintenanceRequest extends BaseTenantModel
{



    protected $table = 'maintenance_requests';
    protected $fillable = array('id', 'customer_id', 'substitutor_id' ,'job_type','asset_id', 'status','emergency_request', 'admin_notes' ,'declined_notes','created_at', 'updated_at');

    public function jobType()
    {
        return $this->belongsTo('JobType', 'job_type');
    }
  
    public function user()
    {
        return $this->belongsTo('User', 'customer_id');
    }
    public function user2()
    {
        return $this->belongsTo('User', 'substitutor_id');
    }

    public function asset()
    {
        return $this->belongsTo('Asset', 'asset_id');
    }

    public function assignRequest()
    {
        return $this->hasMany('AssignRequest', 'request_id');
    }
    public function invoiceRequest()
    {
        return $this->hasMany('Invoice', 'request_id');
    }

    public function requestedService()
    {
        return $this->hasMany('RequestedService', 'request_id');
    }

    public function order()
    {
        return $this->hasMany('Order', 'request_id');
    }

    public static function addMaintenanceRequest($data)
    {
        $maintenance = MaintenanceRequest::create($data);
        return ($maintenance) ? true : false;
    }
    public static function addAdditionalMaintenanceRequest($data)
    {
        $maintenanceID = MaintenanceRequest::create($data);
        return $maintenanceID->id;
    }

    public static function viewAllMaintenanceRequest()
    {
        $requests = self::all();
        return $requests;
    }

    public static function listMaintenanceRequestByCustomerId2($customer_id, $take = 0)
    {

        if ($take == 0) {
            $requests = self::whereRaw('customer_id = ?', array($customer_id))->orderBy('id', 'desc')->get();
        } else {
            $requests = self::whereRaw('customer_id = ? and status = 1', array($customer_id))
                        ->skip(0)
                        ->take($take)->orderBy('id', 'desc')->get();
        }
            return $requests;
    }
    public static function listMaintenanceRequestByCustomerId($customer_id, $take = 0)
    {




        if ($take == 0) {
            $requests = self::whereRaw('customer_id = ? and status = 1', array($customer_id))->orderBy('id', 'desc')

            ->whereHas('Asset', function ($query) {
            // $query->where('customer_type', '=',  Session::get('clientType'));
            })->get();
        } else {
            $requests = self::whereRaw('customer_id = ? and status = 1', array($customer_id))
                            ->skip(0)
                            ->take($take)->orderBy('id', 'desc')
                            ->whereHas('Asset', function ($query) {
                            // $query->where('customer_type', '=',  Session::get('clientType'));
                            })->get();
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

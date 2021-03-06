<?php

namespace App;

class AssignRequestBid extends BaseTenantModel
{

    protected $table = 'assign_request_bids';
    protected $fillable = ['id', 'request_id', 'requested_service_id', 'vendor_id', 'status', 'created_at', 'updated_at'];

    public function requestedService()
    {
        return $this->belongsTo(\App\RequestedBid::class, 'requested_service_id');
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo(\App\MaintenanceBid::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'vendor_id');
    }
    public static function addRequest($data)
    {
        $service = self::create($data);
        return ($service) ? true : false;
    }
    public function assignRequestBidsImage()
    {
        return $this->hasMany(\App\AssignRequestBidsImage::class, 'requested_id');
    }


    public static function deleteRequest($request_id = 0, $vendor_id = 0)
    {

       // Status 2 for Vendor Declined request
        $flag= self::where('request_id', '=', $request_id)
        ->where('vendor_id', '=', $vendor_id)
        ->update(['status' =>2]);
        $MaintenanceRequestData= MaintenanceBid::find($request_id);

    
        return $flag;
    }
    public static function acceptRequest($request_id = 0, $vendor_id = 0)
    {

         // Status 3 for Vendor Accept request
        $flag= self::where('request_id', '=', $request_id)
        ->where('vendor_id', '=', $vendor_id)
        ->update(['status' =>3]);

        //Status Accept for  auto assigned vendor
         EmergencyRequestDetail::where('request_id', '=', $request_id)
                                   ->where('vendor_id', '=', $vendor_id)
                                   ->update(['status' => '3' ]);
        return $flag;
    }

    public static function acceptSingleRequest($request_id = 0, $vendor_id = 0, $service_id = 0)
    {

         // Status 3 for Vendor Accept request
        $flag= self::where('request_id', '=', $request_id)
        ->where('vendor_id', '=', $vendor_id)
        ->where('requested_service_id', '=', $service_id)
        ->update(['status' =>3]);

        //Status Accept for  auto assigned vendor
         EmergencyRequestDetail::where('request_id', '=', $request_id)
                                   ->where('vendor_id', '=', $vendor_id)
                                   ->update(['status' => '3' ]);
        return $flag;
    }


    public static function deleteSingleRequest($request_id = 0, $vendor_id = 0, $service_id)
    {
      // Status 2 for Vendor Declined request
        $flag= self::where('request_id', '=', $request_id)
        ->where('vendor_id', '=', $vendor_id)
        ->where('requested_service_id', '!=', $service_id)
        ->update(['status' =>2]);
        $MaintenanceRequestData= MaintenanceBid::find($request_id);
    }
}

<?php

class AssignRequest extends BaseTenantModel {

    protected $table = 'assign_requests';
    protected $fillable = array('id', 'request_id', 'requested_service_id', 'vendor_id', 'status', 'created_at', 'updated_at');

    public function requestedService() {
        return $this->belongsTo('RequestedService', 'requested_service_id');
    }

    public function maintenanceRequest() {
        return $this->belongsTo('MaintenanceRequest', 'request_id');
    }

    public function user() {
        return $this->belongsTo('User','vendor_id');
    }
    public static function addRequest($data)
    {
       $service = self::create($data);
       return ($service) ? true : false;
   }

   public static function deleteRequest($request_id=0,$vendor_id=0)
   {

     // Status 2 for Vendor Declined request   
    $flag= self::where('request_id', '=', $request_id)
    ->where('vendor_id', '=', $vendor_id)
    ->update(array('status' =>2));
    $MaintenanceRequestData= MaintenanceRequest::find($request_id);

    // if($MaintenanceRequestData->emergency_request==1)
    // {
    //     $EmergencyRequestDetail =  EmergencyRequestDetail::where('request_id','=',$request_id)
    //     ->where('vendor_id','!=',$vendor_id)
    //     ->whereNull('status')
    //     ->orderBy("distance")
    //     ->get();
   

    //         if(isset($EmergencyRequestDetail[0]))
    //         {
    //     foreach ($MaintenanceRequestData->requestedService as $serviceData) {
           
    //         $service_data['request_id'] = $request_id;
    //         $service_data['requested_service_id'] = $serviceData->id;
    //         $service_data['vendor_id'] = $EmergencyRequestDetail[0]->vendor_id;
    //         $service_data['status'] = 1;

    //         AssignRequest::addRequest($service_data);

            
    //     }
            

    //         //Status Declined for previous auto assigned vendor
    //         EmergencyRequestDetail::where('request_id','=',$request_id)
    //                                ->where('vendor_id','=',$vendor_id)
    //                                ->update(array('status' => '2' ));
            
    //         //Status Assigned for new auto assigned vendor
    //         EmergencyRequestDetail::where('request_id','=',$request_id)
    //                                ->where('vendor_id','=',$EmergencyRequestDetail[0]->vendor_id)
    //                                ->update(array('status' => '1' ));
    //                            }
    // }

    return $flag;

}
public static function acceptRequest($request_id=0,$vendor_id=0)
{

     // Status 3 for Vendor Accept request   
    $flag= self::where('request_id', '=', $request_id)
    ->where('vendor_id', '=', $vendor_id)
    ->update(array('status' =>3));

    //Status Accept for  auto assigned vendor
     EmergencyRequestDetail::where('request_id','=',$request_id)
                                   ->where('vendor_id','=',$vendor_id)
                                   ->update(array('status' => '3' ));
    return $flag;

}

public static function acceptSingleRequest($request_id=0,$vendor_id=0,$service_id=0)
{

     // Status 3 for Vendor Accept request   
    $flag= self::where('request_id', '=', $request_id)
    ->where('vendor_id', '=', $vendor_id)
    ->where('requested_service_id', '=', $service_id)
    ->update(array('status' =>3));

    //Status Accept for  auto assigned vendor
     EmergencyRequestDetail::where('request_id','=',$request_id)
                                   ->where('vendor_id','=',$vendor_id)
                                   ->update(array('status' => '3' ));
    return $flag;

}


   public static function deleteSingleRequest($request_id=0,$vendor_id=0,$service_id)
   {
    // Status 2 for Vendor Declined request   
    $flag= self::where('request_id', '=', $request_id)
    ->where('vendor_id', '=', $vendor_id)
     ->where('requested_service_id', '!=', $service_id)
    ->update(array('status' =>2));
    $MaintenanceRequestData= MaintenanceRequest::find($request_id);
    }

}

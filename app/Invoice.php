<?php

class Invoice extends BaseTenantModel
{

    protected $table = 'invoices';
    protected $fillable = ['order_id', 'total_amount', 'request_id', 'user_id' , 'user_type_id' , 'status'];


    public function vendor()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo('User', 'user_id');
    }


    public function order()
    {
          return $this->belongsTo('Order', 'order_id');
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo('MaintenanceRequest', 'request_id');
    }
    public static function listAll($userTypeId = 2, $user_id = "")
    {
        if ($user_id=="") {
            $orders = self::where('user_type_id', '=', $userTypeId)
            ->whereHas('MaintenanceRequest', function ($query) {
                   
                         $query ->whereHas('Asset', function ($query2) {
                            // $query2->where('customer_type', '=',  Session::get('clientType'));
                         });
            })
            ->orderBy('id', 'desc')
       
            ->get();
        } else {
            $orders = self::where('user_type_id', '=', $userTypeId)
            ->where('user_id', '=', $user_id)
            ->whereHas('MaintenanceRequest', function ($query) {
                   
                         $query ->whereHas('Asset', function ($query2) {
                            // $query2->where('customer_type', '=',  Session::get('clientType'));
                         });
            })
            ->orderBy('id', 'desc')
            ->get();
        }
        return $orders;
    }
}

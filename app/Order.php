<?php

class Order extends BaseTenantModel
{

    protected $table = 'orders';
    protected $fillable = array('id', 'request_id', 'vendor_id', 'total_amount', 'status','status_class','status_text', 'created_at', 'updated_at','customer_id','completion_date','approved_date','close_property_status','bid_flag');
    

    public function vendor()
    {
        return $this->belongsTo('User', 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo('User', 'customer_id');
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo('MaintenanceRequest', 'request_id');
    }
    public function maintenanceRequest2()
    {
        return $this->hasMany('request_id');
    }
    public function scopeRequest()
    {
        return $this->belongsTo('MaintenanceRequest', 'request_id');
    }

    public function orderDetail()
    {
        return $this->hasMany('OrderDetail', 'order_id');
    }

    public function orderImage()
    {
        return $this->hasMany('OrderImage', 'order_id');
    }

    public static function listAllWorkOrder()
    {
        $work_orders = self::orderBy('id', 'desc')->get();
        return $work_orders;
    }

    public static function listCompletedOrders()
    {
        //Completed
        $list_completed_order = self::where('status', '=', '2')->orderBy('id', 'desc')->get();
        return $list_completed_order;
    }

    public static function getOrderByID($id)
    {
        $order = self::find($id);
        return $order;
    }

    public static function addOrder($data)
    {
        $order = self::create($data);
        return $order->id;
    }

    
    public static function dashBoardOrders($user_id = null, $status = 0)
    {
        if ($status!=0) {
            $orders = self::where('customer_id', '=', $user_id)

                        ->where('status', '=', $status)
                        ->skip(0)
                        ->take(5)
                        ->orderBy('id', 'desc')
                        ->get();
        } else {
            $orders = self::where('customer_id', '=', $user_id)
            ->skip(0)
            ->take(5)
            ->orderBy('id', 'desc')
            ->get();
        }
        //For all workorder those are recently completed
        $list_orders = array();
        $i = 0;
        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);

            
            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname= $order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                $vendorlastname= $order->vendor->last_name;
            }

            $list_orders[$i]['request_id'] = $order->request_id;
            $list_orders[$i]['vendor_name'] = $vendorfirstname . ' ' . $vendorlastname;
            $list_orders[$i]['asset_number']="";
            if (isset($order->maintenanceRequest->asset->asset_number)) {
                $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            }

            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;


            $list_orders[$i]['service_name'] = '';
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }
         return $list_orders;
    }
}

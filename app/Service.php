<?php

class Service extends BaseTenantModel
{

    protected $table = 'services';
    protected $fillable = ['id', 'title', 'customer_price', 'vendor_price', 'service_code', 'req_date',
     'number_of_men','verified_vacancy','cash_for_keys','cash_for_keys_trash_out','trash_size','storage_shed','lot_size','recurring','emergency','desc','status'
    ,'set_prinkler_system_type','install_temporary_system_type',    'carpet_service_type',  'pool_service_type',    'boarding_type','spruce_up_type','constable_information_type',
    'remove_carpe_type','remove_blinds_type','remove_appliances_type','service_cat_id','customer_type_id','job_type_id' ,'due_date','due_date_val','bid_flag','service_type','vendor_edit'];
    // Defining Function to get User Profile Status. ---- Start
    public static function getAllServices($service_type = 0)
    {
        $service = self::where('status', '=', 1)
                        ->orderBy('title', "ASC")
                        ->where('bid_flag', '=', '0')
                        // ->where('service_type','=',$service_type)
                        ->get();
        return $service; //return user type
    }
  

    public static function getAllServicesBySeviceCategoryId($service_cat_id = "", $job_type = "", $client_type = "")
    {
        $service = self::where('service_cat_id', '=', $service_cat_id)
                      ->where('job_type_id', '=', $job_type)
                      ->where('status', '=', 1)
                      ->where('customer_type_id', '=', $client_type)

                      ->orderBy('title', 'ASC')
                      ->get();
        return $service; //return user type
    }

    public static function getAllServicesBySeviceJobTypeId($job_type_id = "", $client_type = "")
    {
       
        if ($client_type == 6) {
             $service = self::where('job_type_id', '=', $job_type_id)
            ->where('customer_type_id', '=', $client_type)
            ->groupBy('service_cat_id')
            ->orderBy('title', 'ASC')
            ->get();
        } else {
             $service = self::where('job_type_id', '=', $job_type_id)
            ->where('bid_flag', '!=', 1)
            ->where('customer_type_id', '=', $client_type)
            ->groupBy('service_cat_id')
            ->orderBy('title', 'ASC')
            ->get();
        }

       

        return $service; //return user type
    }
    public static function getServicesByClientId($client_type)
    {
        if ($client_type == 6) {
            $service = self::where('status', '!=', 0)
            ->where('customer_type_id', '=', $client_type)
            ->orderBy('title', 'ASC')

            ->get();
        } else {
            $service = self::where('bid_flag', '!=', 1)
            ->where('status', '!=', 0)
            ->where('customer_type_id', '=', $client_type)
            ->orderBy('title', 'ASC')
            ->get();
        }
        return $service;
    }

    public function serviceCategory()
    {

        return $this->belongsTo('ServiceCategory', 'service_cat_id');
    }

    public function requestedService()
    {
        return $this->hasMany('RequestedService', 'service_id');
        //return user type
    }
    
    
    public function specialPrice()
    {
        return $this->hasMany('SpecialPrice', 'service_id');
        //return user type
    }
    
    public function orderDetail()
    {
        return $this->hasMany('OrderDetail', 'service_id');
        //return user type
    }
        
    public static function addAdminService($data)
    {
        $data['status'] = 1;
        $save = self::create($data);
        return ($save) ? $save : false;
    }
    
    public static function updateAdminService($data, $id)
    {
        
        $nulldata=['service_code' =>'' ,
            'title' => '',
            'customer_price' =>  '',
            'vendor_price' => '',
            'desc' => '',
            'recurring' => '',
            'emergency' => '' ,
            'req_date' => '',
            'number_of_men' =>'' ,
            'verified_vacancy' =>'' ,
            'cash_for_keys' =>'' ,
            'cash_for_keys_trash_out' =>'' ,
            'trash_size' => '',
            'storage_shed' =>''  ,
            'lot_size' => '',
            'set_prinkler_system_type' =>'' ,
            'install_temporary_system_type' =>'' ,
            'carpet_service_type' =>'' ,
            'pool_service_type' =>'' ,
            'boarding_type' => '',
            'spruce_up_type' =>'',
            'constable_information_type' =>'',
            'remove_carpe_type' =>'',
            'remove_blinds_type' =>'',
            'remove_appliances_type'=>'',
            'service_type'=>''

            ];
        self::find($id)->update($nulldata);
        
        $save = self::find($id)->update($data);
        return ($save) ? $save : false;
    }
    
    public static function getServices()
    {
        $services = self::orderBy('id', 'desc')
        
        ->get();
        return $services;
    }

    public static function getBidServices()
    {
        $services = self::orderBy('id', 'desc')
        ->where('bid_flag', '=', '1')
        ->get();
        return $services;
    }
    
    
    public static function getServiceById($id)
    {
        $service = self::find($id);
        return $service;
    }
    
    public function getTable()
    {
        return $this->table;
    }
        
    public static function addServicePrice($data, $id)
    {
        $save = self::find($id)->update($data);
        return $save;
    }
    // Defining Function to get User Profile Status. ---- End

    public function getcustomertype()
    {
        return $this->belongsTo('CustomerType', 'customer_type_id');
    }

    public function getjobtype()
    {
        return $this->belongsTo('JobType', 'job_type_id');
    }
}

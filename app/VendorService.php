<?php


class VendorService extends BaseTenantModel
{

    
    protected $table = 'vendor_services';
      
    protected $fillable = array('id', 'vendor_id', 'service_id', 'status', 'created_at' , 'updated_at');
        
    public function user()
    {
        return $this->belongsTo('User');
    }
    public function services()
    {
        return $this->belongsTo('Service', 'service_id');
    }
        
        // Defining Function to get User Profile Status. ---- Start
    public static function addVendorServices($data)
    {
        $service = self::create($data);
        return ($service) ? true : false;
    }
        // Defining Function to get User Profile Status. ---- End

    public static function getAllVendorServices()
    {
        $services = self::where('vendor_id', '=', Auth::user()->id)->get();
        return $services;
    }

    public static function getAllVendorServicesId($id = "")
    {
        $services = self::where('vendor_id', '=', $id)->get();
        return $services;
    }
}

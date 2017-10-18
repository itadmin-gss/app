<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialPrice extends BaseTenantModel
{

    use SoftDeletes;
    protected $table = 'special_prices';
    protected $fillable = ['id', 'customer_id', 'service_id', 'special_price', 'status', 'created_at', 'updated_at','type_id'];
    // Defining Function to get User Profile Status. ---- Start

    public function service()
    {
        return $this->belongsTo(\App\Service::class, 'service_id');
        //return user type
    }
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'customer_id');
        //return user type
    }
    public static function addSpecialPrice($data)
    {
        $save = self::create($data);
        return $save;
    }
    public static function getAllSpecialPrices()
    {
        $special_prices = self::orderBy('id', 'desc')->where('type_id', '=', 2)->get();
        return $special_prices;
    }
    public static function getAllVendorSpecialPrices()
    {
        $special_prices = self::orderBy('id', 'desc')->where('type_id', '=', 3)->get();
        return $special_prices;
    }
    public static function getSpecialPriceByID($id)
    {
        $special_price = self::find($id);
        return $special_price;
    }
    
    public static function updateSpecialPrice($data, $id)
    {
        $save = self::find($id)->update($data);
        return ($save) ? $save : false;
    }
    
    public static function getSpecialCustomerPrice($customer_id, $service_id)
    {

                  $data=self::where('customer_id', '=', $customer_id)
                  ->where('service_id', '=', $service_id)
                  ->first() ;
                  return    $data;
    }
}

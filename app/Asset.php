<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class Asset extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assets';
    protected $fillable = ['id', 'asset_number', 'customer_type','customer_id', 'address', 'city_id', 'state_id', 'zip', 'loan_number','lender', 'property_type', 'lender', 'property_status', 'electric_status', 'water_status', 'gas_status', 'electric_note', 'gas_note', 'water_note', 'status', 'created_at', 'updated_at', 'property_address', 'lock_box', 'access_code', 'brokage', 'agent', 'customer_email_address', 'carbon_copy_email', 'outbuilding_shed', 'outbuilding_shed_note', 'special_direction_note', 'utility_note', 'swimming_pool' ,'occupancy_status','occupancy_status_note','UNIT', 'property_dead_status','property_dead_date','property_dead_user', 'latitude','longitude'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

      // public function newQuery($excludeDeleted = true) {

      //   if(Session::get('clientType')!=""){

      //   return parent::newQuery($excludeDeleted = true)
      //       ->where('customer_type', '=', Session::get('clientType'));
      //   }
      //   else
      //   {
      //       return parent::newQuery($excludeDeleted = true)
      //       ->where('id', '<>', '');
      //   }
      //   }
    public function user()
    {
        return $this->belongsTo('App\User', 'customer_id');
    }

    public function close()
    {
        return $this->belongsTo('App\User', 'property_dead_user');
    }
    public function customerType()
    {
        return $this->belongsTo('App\CustomerType', 'customer_type');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id');
    }
    public function customerTitle()
    {
        return $this->belongsTo('App\CustomerType', 'customer_type');
    }
    public static function addAsset($data, $customer_id)
    {
        $data['status'] = 1;
        $data['customer_id'] = $customer_id;
        $asset = Asset::create($data);
        return ($asset) ? true : false;
    }

    public function maintenanceRequest()
    {
        return $this->hasMany('App\MaintenanceRequest', 'asset_id');
        //return user type
    }

    public function bidRequest()
    {
        return $this->hasMany('App\BidRequest', 'asset_id');
        //return user type
    }

    public static function editAsset($data, $id)
    {
        
        $data['status'] = 1;
        $assetDATA= Asset::find($id);
        if ($data['property_status']!=$assetDATA->property_status) {
                  // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
                
            foreach ($recepient_id as $rec_id) {
                $emailbody='Property '.$id .' property status has been changed to '.$data['property_status'];
                $emailbody.= '<br/>';
                $emailbody.= 'Who Changed it:';
                $emailbody.= '<br/>';
                $emailbody.= 'First Name:'.Auth::user()->first_name;
                $emailbody.= '<br/>';
                $emailbody.= 'Last Name:'.Auth::user()->last_name;
                $emailbody.= '<br/>';
                $emailbody.= 'Property Details:';
                $emailbody.= '<br/>';
                $emailbody.= 'ID:'.$id;
                $emailbody.= '<br/>';
                $emailbody.= 'Property Address'.$assetDATA->property_address;
                $emailbody.= '<br/>';
                $emailbody.= 'City:'.$assetDATA->city->name;
                $emailbody.= '<br/>';
                $emailbody.= 'State:'.$assetDATA->state->name;
                ;
                $emailbody.= '<br/>';
                   

                $url="edit-asset/".$id;
                $emailbody.='To view the Property <a href="http://app.gssreo.com/edit-asset/'.$id.'">please click here</a>!.';



                $userDAta=User::find($rec_id);
        
            
                $email_data = [
                'first_name' => $userDAta['first_name'],
                'last_name' => $userDAta['last_name'],
                'username' => $userDAta['username'],
                'email' => $userDAta['email'],
                'id' =>  $rec_id,
                'user_email_template'=>$emailbody
                       ];
           
                $customervendor="Admin";
                $notification_url="edit-asset/".$id;
              
            //Vendor to admin notification
                $notification = NotificationController::doNotification($rec_id, $rec_id, 'Property '.$id .' has been changed to'.$data['property_status'], 1, $email_data, $notification_url);
                Email::send($userDAta['email'], ': Property Status Notification', 'emails.customer_registered', $email_data);
            }
        }
        
        $updateOrder = Asset::find($id)->update($data);
        return ($updateOrder) ? true : false;
    }

    public static function deleteAsset($id)
    {
        $data['status'] = 0;
        $updateOrder = Asset::find($id)->update($data);
        return ($updateOrder) ? true : false;
    }

    public static function viewAssets()
    {
        //View all asset
        $assets_data = Asset::all();
        return $assets_data;
    }

    public static function viewAssetsByStatus($status)
    {
        $assets = self::whereRaw('status = ?', [$status])->get();
        return $assets;
    }

    public static function getAssetsByCustomerId($id)
    {
        $assets = self::whereRaw("customer_id = ? and property_status != 'closed'", [$id])
        // ->where('customer_type', '=', Session::get('clientType'))
        ->orderBy('id', 'desc')->get();
        return $assets;
    }

    public static function getAssetInformationById($id)
    {
        $asset_information = Asset::find($id);
        return $asset_information;
    }

    public static function requiredFields($flag = 0)
    {
   
        $fields = [
            'asset_number' =>   $flag == 0 ? 'required|unique:assets': 'required',
            'property_address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
            'loan_number' => '',
            'property_type' => 'required',
            'lender' => '',
            'property_status' => 'required',
            'electric_status' => 'required',
            'water_status' => 'required',
            'gas_status' => 'required',
            'lock_box' => 'required',
            'access_code' => '',
            'brokage' => 'required',
            'agent' => 'required',
            'customer_email_address' => '',
            'outbuilding_shed' => 'required',
            'outbuilding_shed_note' => 'required',
            'special_direction_note' => '',
            'utility_note' => '',
            'swimming_pool',
            'occupancy_status'=>'required'
        ];

        return $fields;
    }

    public static function getLatLong($address = "", $zip, $city = "", $state = "")
    {
        $fullAddress = $address." ".$city." ".$state." ".$zip;
   
        $urlAddress = str_replace(" ", "+", $fullAddress);
        $url = "http://maps.google.com/maps/api/geocode/json?address=";
        $url = $url.$urlAddress;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        $result['lat'] = isset($response_a->results[0]->geometry->location->lat)? $response_a->results[0]->geometry->location->lat:"00";
        $result['lng'] = isset($response_a->results[0]->geometry->location->lng)? $response_a->results[0]->geometry->location->lng:"00";
        $result['overall'] = $response_a ;
        return $result;
    }
}

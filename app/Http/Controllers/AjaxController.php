<?php

class AjaxController extends \BaseController {

    public function loadWorkorder($order_id) {
         // get previous user id
        $previous = Order::where('id', '<', $order_id)->where("status","=",2)->max('id');

    // get next user id
        $next = Order::where('id', '>', $order_id)->where("status","=",2)->min('id');

        $data = Order::getOrderByID($order_id);
        
        $order_details = $data->orderDetail;
        $vendorsDATA=User::where('type_id','=',3)->get();
        $items = AdditionalServiceItem::where('order_id','=',$order_id)->get();
        $OrderReviewNote=OrderReviewNote::where('order_id','=',$order_id)->get();
        if (OrderCustomData::where('order_id','=',$order_id)->count() > 0) {
              $customData = OrderCustomData::where('order_id','=',$order_id)->get();
            }else{
                $customData[1] = "lol";
            }
              $order_req = Order::where('id','=',$order_id)->pluck('request_id');
        $services_asset_id= MaintenanceRequest::where('id','=',$order_req)->pluck('asset_id');


        //$job_type =MaintenanceRequest::where('id','=',$order_req)->pluck('job_type');
        
        $client_id = Asset::getAssetInformationById($services_asset_id);
        if (isset($client_id->customer_type)) {
          $allservices = Service::getServicesByClientId($client_id->customer_type);
        }else{
              $allservices = Service::getAllServices();
        }
        
        return View::make('common.quick-view-order')
           ->with('order_details', $order_details)
           ->with('vendorsDATA',$vendorsDATA)
            ->with('order', $data)
            ->with('items', $items)
            ->with('OrderReviewNote', $OrderReviewNote)
            ->with('allservices',$allservices)
            ->with('customData',$customData)
            ->with('previous', $previous)
            ->with('next', $next);
    }
     public function approvedGridExport() {
       $work_orders = Order::where("status","=",4)->update(array('status'=>6,'status_class'=>'blue','status_text'=>'Exported'));
      return "Exported Successfully!";
     }
    //Get all cities by state
    public function getCitiesByState() {
        // Get all post data through ajax
        $data = Input::all();
        //Check if request is ajax
        if (Request::ajax()) {
            //Get all cities by state id from City Model
            $cities = City::getCitiesByStateId($data['state_id']);
            return json_encode($cities);
        } else {
            return false;
        }
    }

    //Get asset information by id
    public function getAssetById() {
        // Get all post data through ajax
        $data = Input::all();
        //Check if request is ajax
        if (Request::ajax()) {
            //Get all asset information by asset_id


        

            $asset_information = Asset::getAssetInformationById($data['asset_id']);
           
           return View::make('pages.customer.asset_information')
            ->with('asset_information', $asset_information)
            ->with('latitude', $asset_information->latitude)
            ->with('longitude', $asset_information->longitude);
            // return json_encode($asset_information);
        } else {
            return false;
        }
    }

    /**
     * Get the popup of service through ajax
     * @params none
     * @return error if asset_number is null or load service information view
     */
    public function getServicePopup() {
        $data = Input::all();

        $id = $data['service_id'];
        $asset_number = $data['asset_number'];

        if ($asset_number != "") {
            
            $last_service_id = $id;
            $service_data = Service::find($id);

       
            $serviceTypeArray=array();
            $serviceValueArray=array();
            $serviceTypes                     =   ServiceFieldDetail::getServiceFieldById($last_service_id);
            $serviceTypeArray['number_of_men']='';
            $serviceTypeArray['verified_vacancy']='';
            $serviceTypeArray['cash_for_keys']='';
            $serviceTypeArray['cash_for_keys_trash_out']=''; 
            $serviceTypeArray['trash_size']='';
            $serviceTypeArray['storage_shed']='';
            $serviceTypeArray['lot_size']='';

            $serviceTypeArray['set_prinkler_system_type']='';
            $serviceTypeArray['install_temporary_system_type']='';
            $serviceTypeArray['carpet_service_type']=''; 
            $serviceTypeArray['pool_service_type']='';
            $serviceTypeArray['boarding_type']='';
            $serviceTypeArray['spruce_up_type']='';
            $serviceTypeArray['constable_information_type']='';
            $serviceTypeArray['remove_carpe_type']='';
            $serviceTypeArray['remove_blinds_type']='';
            $serviceTypeArray['remove_appliances_type']='';
            $serviceTypeArray['due_date']='';


            $serviceValueArray['number_of_men']='';
            $serviceValueArray['verified_vacancy']='';
            $serviceValueArray['cash_for_keys']='';
            $serviceValueArray['cash_for_keys_trash_out']=''; 
            $serviceValueArray['trash_size']='';
            $serviceValueArray['storage_shed']='';
            $serviceValueArray['lot_size']='';

            $serviceValueArray['set_prinkler_system_type']='';
            $serviceValueArray['install_temporary_system_type']='';
            $serviceValueArray['carpet_service_type']=''; 
            $serviceValueArray['pool_service_type']='';
            $serviceValueArray['boarding_type']='';
            $serviceValueArray['spruce_up_type']='';
           

            $serviceValueArray['constable_information_type']='';
            $serviceValueArray['remove_carpe_type']='';
            $serviceValueArray['remove_blinds_type']='';
            $serviceValueArray['remove_appliances_type']='';
            $serviceValueArray['due_date']='';
            $serviceValueArray['emergency']='';



            foreach($serviceTypes as  $value)
            {
                
                $serviceTypeArray[$value->fieldname]=$value->field_type;
                $serviceValueArray[$value->fieldname]=$value->field_values;
        
            }

            return View::make('pages.customer.service_information_popup')
                         ->with('serviceTypeArray',$serviceTypeArray)
                         ->with('serviceValueArray',$serviceValueArray)
                         ->with('service_data', $service_data);
        } else {

            return "error";
        }
    }

    /**
     * Get the popup of service through ajax
     * @params none
     * @return error if asset_number is null or load service information view
     */
    public function getServiceList() {

        $data = Input::all();

        
        $files = Input::file('service_image_' . $data['service_id']);
        $i = 0;
        foreach ($files as $file) {

            if ($file) {
                $destinationPath = Config::get('app.request_images');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                //$unique_id = . $ext;
                $filename = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . '.' . $extension;
                //$filename = $data['service_id'] . '-' . $i . '.' . $extension;
                $data['service_images_' . $data['service_id']][] = $filename;
                $file->move($destinationPath, $filename);
            } else {
                //$data['profile_picture'] = Auth::user()->profile_picture;
            }
            $i++;
        }

        return View::make('pages.customer.service_information_list')
                        ->with('data', $data);
        //return $data;
        //return View::make('pages.customer.service_information_list');
    }


       public function getServiceListOrderReivew() {

        $data = Input::all();

        
        $files = Input::file('service_image_' . $data['service_id']);
        $i = 0;
        foreach ($files as $file) {

            if ($file) {
                $destinationPath = Config::get('app.request_images');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                //$unique_id = . $ext;
                $filename = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . '.' . $extension;
                //$filename = $data['service_id'] . '-' . $i . '.' . $extension;
                $data['service_images_' . $data['service_id']][] = $filename;
                $file->move($destinationPath, $filename);
            } else {
                //$data['profile_picture'] = Auth::user()->profile_picture;
            }
            $i++;
        }

        return View::make('pages.customer.service_information_list_order_reivew')
                        ->with('data', $data);
        //return $data;
        //return View::make('pages.customer.service_information_list');
    }

    public static function generateAssetNumber() {

        $max_id = DB::table('assets')->max('id');
        $asset_id = $max_id + 1;

        $length = strlen($asset_id);
        // General::randomNumber(100, 999)
        $random_number = substr(sha1(mt_rand()),17,5);

        if ($length < 2)
            $random_number .= '0';

        $asset_number = $random_number . $asset_id;
        return $asset_number;
    }

    public static function removeFile() {
        $data = Input::all();

        $path = Config::get('app.request_images');

        unlink($path.'/'.$data['image_name']);

        return $data['image_name'];
    }


    public function testingLink(){
        return View::make('test');
    }

     /**
     * Get the popup of service through ajax
     * @params none
     * @return error if asset_number is null or load service information view
     */
    public function getVendorServicePopup() {
        $data = Input::all();

        $id = $data['service_id'];
        
            $string_ids = implode(',', $id);

            $array_id = explode(',', $string_ids);

            $last_service_id = end($array_id);
            $service_data = Service::find($last_service_id);


            $serviceTypeArray=array();
            $serviceValueArray=array();
            $serviceTypes=   ServiceFieldDetail::getServiceFieldById($last_service_id);
            $serviceTypeArray['number_of_men']='';
            $serviceTypeArray['verified_vacancy']='';
            $serviceTypeArray['cash_for_keys']='';
            $serviceTypeArray['cash_for_keys_trash_out']=''; 
            $serviceTypeArray['trash_size']='';
            $serviceTypeArray['storage_shed']='';
            $serviceTypeArray['lot_size']='';

            $serviceTypeArray['set_prinkler_system_type']='';
            $serviceTypeArray['install_temporary_system_type']='';
            $serviceTypeArray['carpet_service_type']=''; 
            $serviceTypeArray['pool_service_type']='';
            $serviceTypeArray['boarding_type']='';
            $serviceTypeArray['spruce_up_type']='';
            $serviceTypeArray['constable_information_type']='';
            $serviceTypeArray['remove_carpe_type']='';
            $serviceTypeArray['remove_blinds_type']='';
            $serviceTypeArray['remove_appliances_type']='';
            $serviceTypeArray['due_date']='';


            $serviceValueArray['number_of_men']='';
            $serviceValueArray['verified_vacancy']='';
            $serviceValueArray['cash_for_keys']='';
            $serviceValueArray['cash_for_keys_trash_out']=''; 
            $serviceValueArray['trash_size']='';
            $serviceValueArray['storage_shed']='';
            $serviceValueArray['lot_size']='';

            $serviceValueArray['set_prinkler_system_type']='';
            $serviceValueArray['install_temporary_system_type']='';
            $serviceValueArray['carpet_service_type']=''; 
            $serviceValueArray['pool_service_type']='';
            $serviceValueArray['boarding_type']='';
            $serviceValueArray['spruce_up_type']='';
           

            $serviceValueArray['constable_information_type']='';
            $serviceValueArray['remove_carpe_type']='';
            $serviceValueArray['remove_blinds_type']='';
            $serviceValueArray['remove_appliances_type']='';
             $serviceValueArray['due_date']='';


            foreach($serviceTypes as  $value)
            {
                
                $serviceTypeArray[$value->fieldname]=$value->field_type;
                $serviceValueArray[$value->fieldname]=$value->field_values;
        
            }


            return View::make('pages.customer.service_information_popup_vendor')
                          ->with('serviceTypeArray',$serviceTypeArray)
                         ->with('serviceValueArray',$serviceValueArray)
                            ->with('service_data', $service_data);
         
    }

    public function loadServiceOnJobType()
    {
        $Input=Input::all();
       
        $services =  Service::getAllServicesBySeviceJobTypeId($Input['job_type'],$Input['client_type']);
        $dataService=array();

        $options="";
      
        $servicesData=array();
        $i=0;
        foreach ($services as $value) {
         if(isset($value->serviceCategory->title)){
            $servicesData[$i]['title']=$value->serviceCategory->title;
             $servicesData[$i]['service_cat_id']=$value->service_cat_id;
         }
     $i++;
 }





    General::array_sort_by_column($servicesData, 'title');

        foreach ($servicesData as $value) {
         if(isset($value['title'])){
             $options.="<optgroup label='".$value['title']."'>";

             $serviceDataByCategory= Service::getAllServicesBySeviceCategoryId($value['service_cat_id'],$Input['job_type'],$Input['client_type']);

             foreach ($serviceDataByCategory as $serviceDat) {
               if(isset($value['title'])){
                   $options.="<option value='".$serviceDat->id."'>".$serviceDat->title."</option>";

               }

           }
           $options.="</optgroup>";
       }

   }

      $options.="<optgroup label='Not Found Any Service'>";
      $options.="<option value='flagother'>Others</option>";
      $options.="</optgroup>";
 echo  $options;

}

// function saveBidPrice()
// {
//     $statusMessage="";
//   $Input=Input::all();
//    $requestDataBid=RequestedBid::find($Input['id']);
// if(isset($Input['customer_bid_price'])&& $Input['customer_bid_price']!="")
// {

// $data= array(
//     'customer_bid_price' =>  $Input['customer_bid_price'],
//     'vendor_bid_price' =>  $Input['vendor_bid_price']
//     );

// //Status 6  is for when Awaiting Customer Approval
//            MaintenanceBid::where('id','=', $requestDataBid->request_id)
//            ->update(array('status'=>6));
//            $statusMessage="Awaiting Customer Approval";

// }else{
// $data= array(
  
//     'vendor_bid_price' =>  $Input['vendor_bid_price']
//     );

//     $statusMessage="Vendor Price Changed";
//     //Status 3  is for when vendor entered price 
//            MaintenanceBid::where('id','=', $requestDataBid->request_id)
//            ->update(array('status'=>3));

         
// }

//      $MaintenanceBid= MaintenanceBid::find($requestDataBid->request_id);

//     //Notification to Customer
//            $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

//                    $url="list-customer-requested-bids/".$requestDataBid->request_id;
//                    $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 


//             $userDAta=User::find($MaintenanceBid->user->id);
//             $email_data = array(
//             'first_name' => $userDAta->first_name,
//             'last_name' => $userDAta->last_name,
//             'username' => $userDAta->username,
//             'email' => $userDAta->email,
//             'id' =>  $MaintenanceBid->user->id,
//             'user_email_template'=>$emailbody
//                                );

//             $customervendor="Customer";
//             $notification_url="list-customer-requested-bids";
              
//             //Vendor to admin notification
//             $notification = NotificationController::doNotification($MaintenanceBid->user->id,$MaintenanceBid->user->id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to '. $statusMessage, 1,$email_data,$notification_url);
//            Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);
       
//   $save = RequestedBid::where('id','=',$Input['id'])
//           ->update($data); 
//       // $save = AssignRequestBid::where('requested_service_id','=',$Input['id'])
//       //     ->update(array('status'=>2));


// ///Notification to admin

//                    $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

//                    $url="list-bidding-request/".$requestDataBid->request_id;
//                    $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 



//                      // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
//                 $recepient_id = User::getAdminUsersId();
//                 foreach( $recepient_id as $rec_id)
//                 {


//             $userDAta=User::find($rec_id);
//             $email_data = array(
//             'first_name' => $userDAta->first_name,
//             'last_name' => $userDAta->last_name,
//             'username' => $userDAta->username,
//             'email' => $userDAta->email,
//             'id' =>  $rec_id,
//             'user_email_template'=>$emailbody
//                                );

//             $customervendor="Admin";
//             $notification_url="list-bidding-request";
              
//             //Vendor to admin notification
//             $notification = NotificationController::doNotification($rec_id,$rec_id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to Completed Vendor Bid', 1,$email_data,$notification_url);
//            Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);   
       


// }

// }



function saveBidPrice()
{
  $statusMessage="";
  $Input=Input::all();
  $requestDataBid=RequestedBid::find($Input['id']);
  $data=  array();

if(isset($Input['customer_bid_price'])&& $Input['customer_bid_price']!="")
{

$data= array(
    'customer_bid_price' =>  $Input['customer_bid_price'],
    
    'bypassornot'=> $Input['bypassornot']
    );

//Status 6  is for when Awaiting Customer Approval
           MaintenanceBid::where('id','=', $requestDataBid->request_id)
           ->update(array('status'=>6));
           $statusMessage="Awaiting Customer Approval";

}

     $MaintenanceBid= MaintenanceBid::find($requestDataBid->request_id);

          Session::flash('message', "Your Bid has been sent successfully!");
           FlashMessage::displayAlert("Your Bid has been sent successfully!"  , 'success');
    


$assetData=Asset::find($MaintenanceBid->asset_id);

$getServicesforEmail=RequestedBid::where('request_id','=',$MaintenanceBid->id)->get();   
$serviceType="";
foreach ($getServicesforEmail as $getServicesforEmailData) {
    if(isset($getServicesforEmailData->service->title)){
    $serviceType=$getServicesforEmailData->service->title."<br/>";
    }
}
$subject="Subject: ".$assetData->property_address." - Bid Requested \n Bid is ".$statusMessage;  
$url="list-customer-requested-bids/".$requestDataBid->request_id;
$UrlMsg='To view Bid details click here: <a href="http://'.URL::to($url).'">please click here</a>!.'; 

$bidEMailContent=$UrlMsg."<br/>
Request ID:".$requestDataBid->request_id."<br/>
Property Address: ".$assetData->property_address."<br/>
Status: ".$statusMessage."<br/>
Service Type:".$serviceType;


    //Notification to Customer



    $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

     $url="list-customer-requested-bids/".$requestDataBid->request_id;
     $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 



$emailRmainder='Bid '.$requestDataBid->request_id .' Still awaiting Approval';
                   

     $urlRmainder="list-customer-requested-bids/".$requestDataBid->request_id;
     $emailRmainder.='\nTo view the Bid Request <a href="http://'.URL::to($urlRmainder).'">please click here</a>!.'; 



            $userDAta=User::find($MaintenanceBid->user->id);
            $email_data = array(
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $MaintenanceBid->user->id,
            'user_email_template'=>$bidEMailContent
                               );

            $customervendor="Customer";
            $notification_url="list-customer-requested-bids";
              
            //Vendor to admin notification
            $notification = NotificationController::doNotification($MaintenanceBid->user->id,$MaintenanceBid->user->id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to '. $statusMessage, 1,$email_data,$notification_url);
         Email::send($userDAta->email, $subject, 'emails.customer_registered', $email_data);   
       


          $save = RequestedBid::where('id','=',$Input['id'])
          ->update($data); 
      // $save = AssignRequestBid::where('requested_service_id','=',$Input['id'])
      //     ->update(array('status'=>2));


///Notification to admin

                   $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

                   $url="list-bidding-request/".$requestDataBid->request_id;
                   $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 



                     // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
                foreach( $recepient_id as $rec_id)
                {


            $userDAta=User::find($rec_id);
            $email_data = array(
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $rec_id,
            'user_email_template'=>$emailbody
                               );

            $customervendor="Admin";
            $notification_url="list-bidding-request";
              
            //Vendor to admin notification
            $notification = NotificationController::doNotification($rec_id,$rec_id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to Completed Vendor Bid', 1,$email_data,$notification_url);
          Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);   
       


}
if($Input['datepickerremainder']!=""){

//Remainder add
$RemainderData=array(
  'date' => $Input['datepickerremainder'],
  'model'=>'BidRequest',
  'status'=>0,
  'remainder_text'=>$emailRmainder,
  'user_id'=>$MaintenanceBid->user->id,
  'request_id'=>$Input['id']
 );
Remainder::create($RemainderData);
//End Remainder
}
}


function saveBidPriceVendor()
{
  $statusMessage="";
  $Input=Input::all();
  $requestDataBid=RequestedBid::find($Input['id']);
  $data=  array();

if(isset($Input['vendor_bid_price'])&& $Input['vendor_bid_price']!="")
{

$data= array(
    'vendor_bid_price' =>  $Input['vendor_bid_price'],
    
    'vendor_note_for_bid'=> $Input['vendor_note_for_bid']
    );

//Status 3  is for when Completed Vendor Bid
           MaintenanceBid::where('id','=', $requestDataBid->request_id)
           ->update(array('status'=>3));
           $statusMessage="Completed Vendor Bid";

}

     $MaintenanceBid= MaintenanceBid::find($requestDataBid->request_id);

          Session::flash('message', "Thank you. Status is now Completed Vendor Bid.");
           FlashMessage::displayAlert("Thank you for submitting your bid"  , 'success');
    // //Notification to Customer
    // $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

    //  $url="list-customer-requested-bids/".$requestDataBid->request_id;
    //  $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 


    //         $userDAta=User::find($MaintenanceBid->user->id);
    //         $email_data = array(
    //         'first_name' => $userDAta->first_name,
    //         'last_name' => $userDAta->last_name,
    //         'username' => $userDAta->username,
    //         'email' => $userDAta->email,
    //         'id' =>  $MaintenanceBid->user->id,
    //         'user_email_template'=>$emailbody
    //                            );

    //         $customervendor="Customer";
    //         $notification_url="list-customer-requested-bids";
              
    //         //Vendor to admin notification
    //         $notification = NotificationController::doNotification($MaintenanceBid->user->id,$MaintenanceBid->user->id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to '. $statusMessage, 1,$email_data,$notification_url);
    //      Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);   
       


          $save = RequestedBid::where('id','=',$Input['id'])
          ->update($data); 
      // $save = AssignRequestBid::where('requested_service_id','=',$Input['id'])
      //     ->update(array('status'=>2));


///Notification to admin

                   $emailbody='Bid Request '.$requestDataBid->request_id .' status has been changed to '.$statusMessage;
                   

                   $url="list-bidding-request/".$requestDataBid->request_id;
                   $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.'; 



                     // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
                foreach( $recepient_id as $rec_id)
                {


            $userDAta=User::find($rec_id);
            $email_data = array(
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $rec_id,
            'user_email_template'=>$emailbody
                               );

            $customervendor="Admin";
            $notification_url="list-bidding-request";
              
            //Vendor to admin notification
            $notification = NotificationController::doNotification($rec_id,$rec_id, 'Bid Request '.$requestDataBid->request_id .' status has been changed to Completed Vendor Bid', 1,$email_data,$notification_url);
          Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);   
       


}

 //Update status for not sending remainder emails
            Remainder::where('request_id','=', $requestDataBid->request_id)
            ->update(array('status'=>1));
}



}

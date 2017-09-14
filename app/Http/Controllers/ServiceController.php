<?php

class ServiceController extends \BaseController
{



    public function index()
    {



        $services=Service::getAllServices();



        return View::make('pages.vendors.vendor_profile_service')->with('services', $services);
    }

    public function addRequestedService($id)
    {
        $data = Input::all();
        $data['order_id'] = $id;
        
        $customers_notes = Input::get('customers_notes');
        $vendors_notes = Input::get('vendors_notes');
        $notes_for_vendors = Input::get('notes_for_vendors');
        $admin_quantity = Input::get('admin_quantity');
        $quantity = Input::get('quantity');
        $customer_price = Input::get('customer_price');
        $vendor_price = Input::get('vendors_price');
        
        $Dataexist = OrderCustomData::where('order_id', '=', $id)->count();
        if ($Dataexist==0) {
            $record = OrderCustomData::createCustomData($data);
        } else {
            if (Auth::user()->type_id == 3) {
                $record =  OrderCustomData::where("order_id", '=', $id)->update(array('quantity' => $quantity,'vendors_price'=>$vendor_price));
            } else {
                $record =  OrderCustomData::where("order_id", '=', $id)->update(array('customers_notes' => $customers_notes ,'admin_quantity' => $admin_quantity,  'quantity' => $quantity, 'vendors_notes' => $vendors_notes ,'notes_for_vendors'=>$notes_for_vendors, 'customer_price' =>$customer_price,'vendors_price'=>$vendor_price));
            }
        }
        // echo $record;
        if ($record) {
                        return $data;
        } else {
            return false;
        }
    }
    
    public function updateAdditionalItem($id)
    {

        $description = Input::get('description');
        $adminQuantity = Input::get('admin_quantity');
        $quantity = Input::get('quantity');
        $rate = Input::get('rate');
        $customer_price = Input::get('customer_price');
        print_r(Input::all());
        $record =  AdditionalServiceItem::where("id", '=', $id)->update(array('description' => $description ,'admin_quantity'=>$adminQuantity, 'quantity' => $quantity, 'rate' => $rate , 'customer_price' =>$customer_price));

        echo $record;
    }
    public function addAdditionalItem()
    {
        $data = Input::all();
    
        $id =  AdditionalServiceItem::Add_Items($data);

           $emailUrl="edit-order/".$data['order_id'];
           $userDAta=User::find($data['vendor_id']);
            $recepient_id = User::getOnlyAdminUsersId();
           
         
            $email_data = array(
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $data['vendor_id'],
            'user_email_template'=>"Additonal Service ".$id."  has been created! To view it in Work Order, <a href='".URL::to($emailUrl)."'>please click here</a>!"
            );

            // $customervendor="Vendor";
            $notification_url="list-work-order-admin";
              
            //Vendor to admin notification
           $noti_message =  "New Additional Service has been Created. Order# ".$data['order_id'];
            $notification = NotificationController::doNotification($recepient_id['id'], $data['vendor_id'], $noti_message, 1, $email_data, $notification_url);
           
            $email = Email::send($recepient_id['email'], 'GSS Additional Service Notification', 'emails.customer_registered', $email_data);
            

                    
        return $id;
    }
    public function checkAddedTitle()
    {
        $data = Input::all();
        $id = Input::get('id');
        foreach ($data as $serviceTitle) {
            $services = DB::table('services')->where('id', '=', $id)->orderBy('created_at', 'desc')->first();
        }
        if (isset($services)) {
            $desc = $services->desc;
            $vendor_price = $services->vendor_price;
            $customer_price = $services->customer_price;
            $serviceItems = array($desc, $vendor_price, $customer_price);
            return $serviceItems;
        } else {
            return "";
        }
    }
    public function assignVendorService()
    {

        $rules = array(

            'services' => 'required',

        );

        $validator = Validator::make(Input::all(), $rules);



        // process the login

        if ($validator->fails()) {
            return Redirect::to('vendor-profile-service')

                            ->withErrors($validator);
        } else {
            $data=Input::get('services');

            $user_data=Auth::user();

            foreach ($data as $service_id) {
                $service_data['service_id']=$service_id;

                $service_data['vendor_id']=$user_data->id;

                $service_data['status']=1;

                $save=VendorService::addVendorServices($service_data);

                if (!$save) {
                    break;

                    return Redirect::back()

                    ->with('message', 'Data has not been saved due to some problems');
                }
            }

            return Redirect::to('vendors');

            /*

            $user = new Users;

            $user->first_name = Input::get('first_name');

            $user->last_name = Input::get('last_name');

            $user->email = Input::get('email');

            $user->company = Input::get('company');

            $user->username = Input::get('username');

            $user->type_id = Input::get('type_id') ? Input::get('type_id') : NULL;

            $user->user_role_id = '0';

            $user->status = '1';

            $user->password = Hash::make(Input::get('password'));



            if ($user->save()) {

                $id = $user->id;



                $email_data = array(

                    'first_name' => Input::get('first_name'),

                    'last_name' => Input::get('last_name'),

                    'username' => Input::get('username'),

                    'email' => Input::get('email'),

                    'id' => $id,

                );



                return Redirect::to('/');

            }

             */
        }
    }



    public static function addAdminService()
    {

        $submitted = Input::get('submitted');

        if ($submitted) {
            $data = Input::all();

            unset($data['_token']);

            unset($data['submitted']);

            //echo '<pre>'; print_r($data); exit;



             $rules = array(

                        'service_code' => 'required',

                        'title' => 'required',

                        'customer_price' => 'required',

                        'vendor_price' => 'required'

                    );



             $validator = Validator::make(Input::all(), $rules); // put all rules to validator

             // if validation is failed redirect to add customer asset with errors

            if ($validator->fails()) {
                return Redirect::back()

                              ->withErrors($validator);
            } else {
                $save = Service::addAdminService($data);

                $serviceID=DB::getPdo()->lastInsertId();

                if ($save) {
                    if (isset($data['number_of_men'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'number_of_men',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['number_of_men_type'],

                                                    'field_values'=>$data['number_of_men_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }





                    if (isset($data['verified_vacancy'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'verified_vacancy',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['verified_vacancy_type'],

                                                    'field_values'=>$data['verified_vacancy_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['cash_for_keys'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'cash_for_keys',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['cash_for_keys_type'],

                                                    'field_values'=>$data['cash_for_keys_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['cash_for_keys_trash_out'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'cash_for_keys_trash_out',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['cash_for_keys_trash_out_type'],

                                                    'field_values'=>$data['cash_for_keys_trash_out_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['trash_size'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'trash_size',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['trash_size_type'],

                                                    'field_values'=>$data['trash_size_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['storage_shed'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'storage_shed',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['storage_shed_type'],

                                                    'field_values'=>$data['storage_shed_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    







                    if (isset($data['set_prinkler_system_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'set_prinkler_system_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['set_prinkler_system_type_type'],

                                                    'field_values'=>$data['set_prinkler_system_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



    

                    if (isset($data['install_temporary_system_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'install_temporary_system_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['install_temporary_system_type_type'],

                                                    'field_values'=>$data['install_temporary_system_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }





                    if (isset($data['carpet_service_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'carpet_service_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['carpet_service_type_type'],

                                                    'field_values'=>$data['carpet_service_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['pool_service_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'pool_service_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['pool_service_type_type'],

                                                    'field_values'=>$data['pool_service_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }





   

                    if (isset($data['boarding_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'boarding_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['boarding_type_type'],

                                                    'field_values'=>$data['boarding_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['spruce_up_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'spruce_up_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['spruce_up_type_type'],

                                                    'field_values'=>$data['spruce_up_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['lot_size'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'lot_size_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['lot_size_type'],

                                                    'field_values'=>$data['lot_size_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['constable_information_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'constable_information_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['constable_information_type_type'],

                                                    'field_values'=>$data['constable_information_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['remove_carpe_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'remove_carpe_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_carpe_type_type'],

                                                    'field_values'=>$data['remove_carpe_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['remove_blinds_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'remove_blinds_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_blinds_type_type'],

                                                    'field_values'=>$data['remove_blinds_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    



                    if (isset($data['remove_appliances_type'])) {
                        $ServicesFieldsDetailData=array('fieldname'=>'remove_appliances_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_appliances_type_type'],

                                                    'field_values'=>$data['remove_appliances_type_values']

                                                    );

                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    

                    



                    

                    $message = FlashMessage::messages('admin_service.service_added');

                    return Redirect::to('list-services')

                           ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                } else {
                    $message = FlashMessage::messages('admin_service.service_error');

                    return Redirect::back()

                           ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                }
            }
        } else {
            $ServiceCategory=ServiceCategory::get();

            $CustomerType=CustomerType::get();

            $JobType=JobType::get();

            $typeArray  =  array(  "select"    => "select",

                                "text"      => "text",

                                "checkbox"  => "checkbox",

                                "radio"     => "radio"

                            );



            return View::make('pages.admin.add_service')

                        ->with('typeArray', $typeArray)

                        ->with('ServiceCategory', $ServiceCategory)

                        ->with('CustomerType', $CustomerType)

                        ->with('JobType', $JobType);
        }
    }



    public static function updateAdminService($service_id)
    {

        $submitted = Input::get('submitted');

        if ($submitted) {
            $data = Input::except('_token', 'submitted');

            //echo '<pre>'; print_r($data); exit;



             $rules = array(

                        'service_code' => 'required',

                        'title' => 'required',

                        'customer_price' => 'required',

                        'vendor_price' => 'required'

                    );



             $validator = Validator::make($data, $rules); // put all rules to validator

             // if validation is failed redirect to add customer asset with errors

            if ($validator->fails()) {
                return Redirect::back()

                              ->withErrors($validator);
            } else {
                $vendor_edit =  Input::get("vendor_edit");
            
                Service::where('id', '=', $service_id)->update(array('vendor_edit'=>$vendor_edit));
            

                $save = Service::updateAdminService($data, $service_id);

                if ($save) {
                       $serviceTypes= ServiceFieldDetail::getServiceFieldById($service_id);

                        



                    if (count($serviceTypes)!=0) {
                        if (isset($data['number_of_men'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['number_of_men_type'],

                                                    'field_values'=>$data['number_of_men_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'number_of_men')

                                            ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['verified_vacancy'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['verified_vacancy_type'],

                                                'field_values'=>$data['verified_vacancy_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'verified_vacancy')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['cash_for_keys'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['cash_for_keys_type'],

                                                'field_values'=>$data['cash_for_keys_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'cash_for_keys')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['cash_for_keys_trash_out'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['cash_for_keys_trash_out_type'],

                                                'field_values'=>$data['cash_for_keys_trash_out_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'cash_for_keys_trash_out')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['trash_size'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['trash_size_type'],

                                                'field_values'=>$data['trash_size_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'trash_size')

                                        ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['number_of_men'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['number_of_men_type'],

                                                'field_values'=>$data['number_of_men_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'number_of_men')

                                        ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['storage_shed'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['storage_shed_type'],

                                                'field_values'=>$data['storage_shed_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'storage_shed')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['lot_size'])) {
                            //Fixed by Ali

                            //echo '<pre>';print_r($data); print_r($serviceTypes); exit; in db column name fieldname value was lot_size_type which changed to lot_size for fix

                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['lot_size_type'],

                                                'field_values'=>$data['lot_size_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'lot_size')

                                        ->update($ServicesFieldsDetailData);
                        }





                        if (isset($data['set_prinkler_system_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['set_prinkler_system_type_type'],

                                                    'field_values'=>$data['set_prinkler_system_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'set_prinkler_system_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['install_temporary_system_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['install_temporary_system_type_type'],

                                                    'field_values'=>$data['install_temporary_system_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'install_temporary_system_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['carpet_service_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['carpet_service_type_type'],

                                                    'field_values'=>$data['carpet_service_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'carpet_service_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['pool_service_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['pool_service_type_type'],

                                                    'field_values'=>$data['pool_service_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'pool_service_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['boarding_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['boarding_type_type'],

                                                    'field_values'=>$data['boarding_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'boarding_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['spruce_up_type'])) {
                            $ServicesFieldsDetailData=array(

                                                    'field_type'=>$data['spruce_up_type_type'],

                                                    'field_values'=>$data['spruce_up_type_values']

                                                    );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'spruce_up_type')

                                            ->update($ServicesFieldsDetailData);
                        }

                        if (isset($data['constable_information_type'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['constable_information_type_type'],

                                                'field_values'=>$data['constable_information_type_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'constable_information_type')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_carpe_type'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['remove_carpe_type_type'],

                                                'field_values'=>$data['remove_carpe_type_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_carpe_type')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_blinds_type'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['remove_blinds_type_type'],

                                                'field_values'=>$data['remove_blinds_type_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_blinds_type')

                                        ->update($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_appliances_type'])) {
                            $ServicesFieldsDetailData=array(

                                                'field_type'=>$data['remove_appliances_type_type'],

                                                'field_values'=>$data['remove_appliances_type_values']

                                                );

                            ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_appliances_type')

                                        ->update($ServicesFieldsDetailData);
                        }
                    } else {
                        if (isset($data['number_of_men'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'number_of_men',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['number_of_men_type'],

                                             'field_values'=>$data['number_of_men_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                 



                        if (isset($data['cash_for_keys_trash_out'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'cash_for_keys_trash_out',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['cash_for_keys_trash_out_type'],

                                             'field_values'=>$data['cash_for_keys_trash_out_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                        if (isset($data['trash_size'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'trash_size',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['trash_size_type'],

                                             'field_values'=>$data['trash_size_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['storage_shed'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'storage_shed',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['storage_shed_type'],

                                             'field_values'=>$data['storage_shed_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                        if (isset($data['set_prinkler_system_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'set_prinkler_system_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['set_prinkler_system_type_type'],

                                             'field_values'=>$data['set_prinkler_system_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



    

                        if (isset($data['install_temporary_system_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'install_temporary_system_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['install_temporary_system_type_type'],

                                             'field_values'=>$data['install_temporary_system_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                        if (isset($data['carpet_service_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'carpet_service_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['carpet_service_type_type'],

                                             'field_values'=>$data['carpet_service_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['pool_service_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'pool_service_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['pool_service_type_type'],

                                             'field_values'=>$data['pool_service_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





   

                        if (isset($data['boarding_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'boarding_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['boarding_type_type'],

                                             'field_values'=>$data['boarding_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['spruce_up_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'spruce_up_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['spruce_up_type_type'],

                                             'field_values'=>$data['spruce_up_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                        if (isset($data['lot_size'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'lot_size',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['lot_size_type'],

                                             'field_values'=>$data['lot_size_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                        if (isset($data['constable_information_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'constable_information_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['constable_information_type_type'],

                                             'field_values'=>$data['constable_information_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                    

                        if (isset($data['remove_carpe_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'remove_carpe_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_carpe_type_type'],

                                             'field_values'=>$data['remove_carpe_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_blinds_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'remove_blinds_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_blinds_type_type'],

                                             'field_values'=>$data['remove_blinds_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                    

                        if (isset($data['remove_appliances_type'])) {
                            $ServicesFieldsDetailData=array('fieldname'=>'remove_appliances_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_appliances_type_type'],

                                             'field_values'=>$data['remove_appliances_type_values']

                                             );

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }
                    }





                    $message = FlashMessage::messages('admin_service.service_updated');

                    return Redirect::to('list-services')

                       ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                } else {
                    $message = FlashMessage::messages('admin_service.service_error');

                    return Redirect::back()

                           ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                }
            }
        } else {
            $serviceTypeArray=array();

            $serviceValueArray=array();







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

            $serviceTypeArray['due_date_val']='';



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



            $serviceTypes= ServiceFieldDetail::getServiceFieldById($service_id);

        //print_r($serviceTypes);

            foreach ($serviceTypes as $value) {
                $serviceTypeArray[$value->fieldname]=$value->field_type;

                $serviceValueArray[$value->fieldname]=$value->field_values;
            }





             $typeArray  =  array(

                                "select"    => "select",

                                "text"      => "text",

                                "checkbox"  => "checkbox",

                                "radio"     => "radio"

                                );

            $service = Service::getServiceById($service_id);



            $ServiceCategory = ServiceCategory::get();

            $CustomerType    = CustomerType::get();

            $JobType         = JobType::get();



            return View::make('pages.admin.edit_service')

            ->with('typeArray', $typeArray)

            ->with('serviceTypeArray', $serviceTypeArray)

            ->with('serviceValueArray', $serviceValueArray)

            ->with(array('service' => $service))

            ->with('ServiceCategory', $ServiceCategory)

            ->with('CustomerType', $CustomerType)

            ->with('JobType', $JobType);
        }
    }



    public static function listAdminServices()
    {

        $services = Service::getServices();

        $serv = new Service;

        $db_table = $serv->getTable();

        return View::make('pages.admin.list_services')->with(array('services' => $services,

        'db_table' => $db_table ));
    }
}

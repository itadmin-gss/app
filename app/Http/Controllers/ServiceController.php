<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\CustomerType;
use App\JobType;
use App\OrderCustomData;
use App\Service;
use App\ServiceCategory;
use App\ServiceFieldDetail;
use App\User;
use App\VendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{



    public function index()
    {



        $services=Service::getAllServices();



        return view('pages.vendors.vendor_profile_service')->with('services', $services);
    }

    public function addRequestedService($id)
    {
        $data = Request::all();
        $data['order_id'] = $id;
        
        $customers_notes = Request::get('customers_notes');
        $vendors_notes = Request::get('vendors_notes');
        $notes_for_vendors = Request::get('notes_for_vendors');
        $admin_quantity = Request::get('admin_quantity');
        $quantity = Request::get('quantity');
        $customer_price = Request::get('customer_price');
        $vendor_price = Request::get('vendors_price');
        
        $Dataexist = OrderCustomData::where('order_id', '=', $id)->count();
        if ($Dataexist==0) {
            $record = OrderCustomData::createCustomData($data);
        } else {
            if (Auth::user()->type_id == 3) {
                $record =  OrderCustomData::where("order_id", '=', $id)->update(['quantity' => $quantity,'vendors_price'=>$vendor_price]);
            } else {
                $record =  OrderCustomData::where("order_id", '=', $id)->update(['customers_notes' => $customers_notes ,'admin_quantity' => $admin_quantity,  'quantity' => $quantity, 'vendors_notes' => $vendors_notes ,'notes_for_vendors'=>$notes_for_vendors, 'customer_price' =>$customer_price,'vendors_price'=>$vendor_price]);
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

        $description = Request::get('description');
        $adminQuantity = Request::get('admin_quantity');
        $quantity = Request::get('quantity');
        $rate = Request::get('rate');
        $customer_price = Request::get('customer_price');
        print_r(Request::all());
        $record =  AdditionalServiceItem::where("id", '=', $id)->update(['description' => $description ,'admin_quantity'=>$adminQuantity, 'quantity' => $quantity, 'rate' => $rate , 'customer_price' =>$customer_price]);

        echo $record;
    }
    public function addAdditionalItem()
    {
        $data = Request::all();
    
        $id =  AdditionalServiceItem::Add_Items($data);

           $emailUrl="edit-order/".$data['order_id'];
           $userDAta=User::find($data['vendor_id']);
            $recepient_id = User::getOnlyAdminUsersId();
           
         
            $email_data = [
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $data['vendor_id'],
            'user_email_template'=>"Additonal Service ".$id."  has been created! To view it in Work Order, <a href='".URL::to($emailUrl)."'>please click here</a>!"
            ];

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
        $data = Request::all();
        $id = Request::get('id');
        foreach ($data as $serviceTitle) {
            $services = DB::table('services')->where('id', '=', $id)->orderBy('created_at', 'desc')->first();
        }
        if (isset($services)) {
            $desc = $services->desc;
            $vendor_price = $services->vendor_price;
            $customer_price = $services->customer_price;
            $serviceItems = [$desc, $vendor_price, $customer_price];
            return $serviceItems;
        } else {
            return "";
        }
    }
    public function assignVendorService()
    {

        $rules = [

            'services' => 'required',

        ];

        $validator = Validator::make(Request::all(), $rules);



        // process the login

        if ($validator->fails()) {
            return redirect('vendor-profile-service')

                            ->withErrors($validator);
        } else {
            $data=Request::get('services');

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

            return redirect('vendors');

            /*

            $user = new Users;

            $user->first_name = Request::get('first_name');

            $user->last_name = Request::get('last_name');

            $user->email = Request::get('email');

            $user->company = Request::get('company');

            $user->username = Request::get('username');

            $user->type_id = Request::get('type_id') ? Request::get('type_id') : NULL;

            $user->user_role_id = '0';

            $user->status = '1';

            $user->password = Hash::make(Request::get('password'));



            if ($user->save()) {

                $id = $user->id;



                $email_data = array(

                    'first_name' => Request::get('first_name'),

                    'last_name' => Request::get('last_name'),

                    'username' => Request::get('username'),

                    'email' => Request::get('email'),

                    'id' => $id,

                );



                return redirect('/');

            }

             */
        }
    }



    public static function addAdminService()
    {

        $submitted = Request::get('submitted');

        if ($submitted) {
            $data = Request::all();

            unset($data['_token']);

            unset($data['submitted']);

            //echo '<pre>'; print_r($data); exit;



             $rules = [

                        'service_code' => 'required',

                        'title' => 'required',

                        'customer_price' => 'required',

                        'vendor_price' => 'required'

                    ];



             $validator = Validator::make(Request::all(), $rules); // put all rules to validator

             // if validation is failed redirect to add customer asset with errors

             if ($validator->fails()) {
                 return Redirect::back()

                              ->withErrors($validator);
             } else {
                    $save = Service::addAdminService($data);

                    $serviceID=DB::getPdo()->lastInsertId();

                    if ($save) {
                        if (isset($data['number_of_men'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'number_of_men',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['number_of_men_type'],

                                                    'field_values'=>$data['number_of_men_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                        if (isset($data['verified_vacancy'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'verified_vacancy',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['verified_vacancy_type'],

                                                    'field_values'=>$data['verified_vacancy_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['cash_for_keys'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'cash_for_keys',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['cash_for_keys_type'],

                                                    'field_values'=>$data['cash_for_keys_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['cash_for_keys_trash_out'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'cash_for_keys_trash_out',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['cash_for_keys_trash_out_type'],

                                                    'field_values'=>$data['cash_for_keys_trash_out_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                        if (isset($data['trash_size'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'trash_size',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['trash_size_type'],

                                                    'field_values'=>$data['trash_size_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['storage_shed'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'storage_shed',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['storage_shed_type'],

                                                    'field_values'=>$data['storage_shed_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                    







                        if (isset($data['set_prinkler_system_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'set_prinkler_system_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['set_prinkler_system_type_type'],

                                                    'field_values'=>$data['set_prinkler_system_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



    

                        if (isset($data['install_temporary_system_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'install_temporary_system_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['install_temporary_system_type_type'],

                                                    'field_values'=>$data['install_temporary_system_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





                        if (isset($data['carpet_service_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'carpet_service_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['carpet_service_type_type'],

                                                    'field_values'=>$data['carpet_service_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['pool_service_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'pool_service_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['pool_service_type_type'],

                                                    'field_values'=>$data['pool_service_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }





   

                        if (isset($data['boarding_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'boarding_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['boarding_type_type'],

                                                    'field_values'=>$data['boarding_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['spruce_up_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'spruce_up_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['spruce_up_type_type'],

                                                    'field_values'=>$data['spruce_up_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                        if (isset($data['lot_size'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'lot_size_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['lot_size_type'],

                                                    'field_values'=>$data['lot_size_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['constable_information_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'constable_information_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['constable_information_type_type'],

                                                    'field_values'=>$data['constable_information_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_carpe_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'remove_carpe_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_carpe_type_type'],

                                                    'field_values'=>$data['remove_carpe_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }



                        if (isset($data['remove_blinds_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'remove_blinds_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_blinds_type_type'],

                                                    'field_values'=>$data['remove_blinds_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                    



                        if (isset($data['remove_appliances_type'])) {
                            $ServicesFieldsDetailData=['fieldname'=>'remove_appliances_type',

                                                    'service_id'=> $serviceID,

                                                    'field_type'=>$data['remove_appliances_type_type'],

                                                    'field_values'=>$data['remove_appliances_type_values']

                                                    ];

                            ServiceFieldDetail::add($ServicesFieldsDetailData);
                        }

                    

                    



                    

                        $message = FlashMessage::messages('admin_service.service_added');

                        return redirect('list-services')

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

            $typeArray  =  [  "select"    => "select",

                                "text"      => "text",

                                "checkbox"  => "checkbox",

                                "radio"     => "radio"

                            ];



            return view('pages.admin.add_service')

                        ->with('typeArray', $typeArray)

                        ->with('ServiceCategory', $ServiceCategory)

                        ->with('CustomerType', $CustomerType)

                        ->with('JobType', $JobType);
        }
    }



    public static function updateAdminService($service_id)
    {

        $submitted = Request::get('submitted');

        if ($submitted) {
            $data = Request::except('_token', 'submitted');

            //echo '<pre>'; print_r($data); exit;



             $rules = [

                        'service_code' => 'required',

                        'title' => 'required',

                        'customer_price' => 'required',

                        'vendor_price' => 'required'

                    ];



             $validator = Validator::make($data, $rules); // put all rules to validator

             // if validation is failed redirect to add customer asset with errors

             if ($validator->fails()) {
                 return Redirect::back()

                              ->withErrors($validator);
             } else {
                    $vendor_edit =  Request::get("vendor_edit");
            
                    Service::where('id', '=', $service_id)->update(['vendor_edit'=>$vendor_edit]);
            

                    $save = Service::updateAdminService($data, $service_id);

                    if ($save) {
                           $serviceTypes= ServiceFieldDetail::getServiceFieldById($service_id);

                        



                        if (count($serviceTypes)!=0) {
                            if (isset($data['number_of_men'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['number_of_men_type'],

                                                    'field_values'=>$data['number_of_men_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'number_of_men')

                                            ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['verified_vacancy'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['verified_vacancy_type'],

                                                'field_values'=>$data['verified_vacancy_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'verified_vacancy')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['cash_for_keys'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['cash_for_keys_type'],

                                                'field_values'=>$data['cash_for_keys_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'cash_for_keys')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['cash_for_keys_trash_out'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['cash_for_keys_trash_out_type'],

                                                'field_values'=>$data['cash_for_keys_trash_out_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'cash_for_keys_trash_out')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['trash_size'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['trash_size_type'],

                                                'field_values'=>$data['trash_size_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'trash_size')

                                        ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['number_of_men'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['number_of_men_type'],

                                                'field_values'=>$data['number_of_men_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'number_of_men')

                                        ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['storage_shed'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['storage_shed_type'],

                                                'field_values'=>$data['storage_shed_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'storage_shed')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['lot_size'])) {
                                //Fixed by Ali

                                //echo '<pre>';print_r($data); print_r($serviceTypes); exit; in db column name fieldname value was lot_size_type which changed to lot_size for fix

                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['lot_size_type'],

                                                'field_values'=>$data['lot_size_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'lot_size')

                                        ->update($ServicesFieldsDetailData);
                            }





                            if (isset($data['set_prinkler_system_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['set_prinkler_system_type_type'],

                                                    'field_values'=>$data['set_prinkler_system_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'set_prinkler_system_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['install_temporary_system_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['install_temporary_system_type_type'],

                                                    'field_values'=>$data['install_temporary_system_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'install_temporary_system_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['carpet_service_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['carpet_service_type_type'],

                                                    'field_values'=>$data['carpet_service_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'carpet_service_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['pool_service_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['pool_service_type_type'],

                                                    'field_values'=>$data['pool_service_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'pool_service_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['boarding_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['boarding_type_type'],

                                                    'field_values'=>$data['boarding_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'boarding_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['spruce_up_type'])) {
                                $ServicesFieldsDetailData=[

                                                    'field_type'=>$data['spruce_up_type_type'],

                                                    'field_values'=>$data['spruce_up_type_values']

                                                    ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                            ->where('fieldname', '=', 'spruce_up_type')

                                            ->update($ServicesFieldsDetailData);
                            }

                            if (isset($data['constable_information_type'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['constable_information_type_type'],

                                                'field_values'=>$data['constable_information_type_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'constable_information_type')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['remove_carpe_type'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['remove_carpe_type_type'],

                                                'field_values'=>$data['remove_carpe_type_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_carpe_type')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['remove_blinds_type'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['remove_blinds_type_type'],

                                                'field_values'=>$data['remove_blinds_type_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_blinds_type')

                                        ->update($ServicesFieldsDetailData);
                            }



                            if (isset($data['remove_appliances_type'])) {
                                $ServicesFieldsDetailData=[

                                                'field_type'=>$data['remove_appliances_type_type'],

                                                'field_values'=>$data['remove_appliances_type_values']

                                                ];

                                ServiceFieldDetail::where('service_id', '=', $service_id)

                                        ->where('fieldname', '=', 'remove_appliances_type')

                                        ->update($ServicesFieldsDetailData);
                            }
                        } else {
                            if (isset($data['number_of_men'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'number_of_men',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['number_of_men_type'],

                                             'field_values'=>$data['number_of_men_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }





                 



                            if (isset($data['cash_for_keys_trash_out'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'cash_for_keys_trash_out',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['cash_for_keys_trash_out_type'],

                                             'field_values'=>$data['cash_for_keys_trash_out_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }

                            if (isset($data['trash_size'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'trash_size',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['trash_size_type'],

                                             'field_values'=>$data['trash_size_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }



                            if (isset($data['storage_shed'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'storage_shed',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['storage_shed_type'],

                                             'field_values'=>$data['storage_shed_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }





                            if (isset($data['set_prinkler_system_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'set_prinkler_system_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['set_prinkler_system_type_type'],

                                             'field_values'=>$data['set_prinkler_system_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }



    

                            if (isset($data['install_temporary_system_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'install_temporary_system_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['install_temporary_system_type_type'],

                                             'field_values'=>$data['install_temporary_system_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }





                            if (isset($data['carpet_service_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'carpet_service_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['carpet_service_type_type'],

                                             'field_values'=>$data['carpet_service_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }



                            if (isset($data['pool_service_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'pool_service_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['pool_service_type_type'],

                                             'field_values'=>$data['pool_service_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }





   

                            if (isset($data['boarding_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'boarding_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['boarding_type_type'],

                                             'field_values'=>$data['boarding_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }



                            if (isset($data['spruce_up_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'spruce_up_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['spruce_up_type_type'],

                                             'field_values'=>$data['spruce_up_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }





                            if (isset($data['lot_size'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'lot_size',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['lot_size_type'],

                                             'field_values'=>$data['lot_size_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }

                            if (isset($data['constable_information_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'constable_information_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['constable_information_type_type'],

                                             'field_values'=>$data['constable_information_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }

                    

                            if (isset($data['remove_carpe_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'remove_carpe_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_carpe_type_type'],

                                             'field_values'=>$data['remove_carpe_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }



                            if (isset($data['remove_blinds_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'remove_blinds_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_blinds_type_type'],

                                             'field_values'=>$data['remove_blinds_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }

                    

                            if (isset($data['remove_appliances_type'])) {
                                $ServicesFieldsDetailData=['fieldname'=>'remove_appliances_type',

                                             'service_id'=> $service_id,

                                             'field_type'=>$data['remove_appliances_type_type'],

                                             'field_values'=>$data['remove_appliances_type_values']

                                             ];

                                ServiceFieldDetail::add($ServicesFieldsDetailData);
                            }
                        }





                        $message = FlashMessage::messages('admin_service.service_updated');

                        return redirect('list-services')

                           ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                    } else {
                        $message = FlashMessage::messages('admin_service.service_error');

                        return Redirect::back()

                        ->with('message', FlashMessage::DisplayAlert($message, 'success'));
                    }
                   }
        } else {
            $serviceTypeArray=[];

            $serviceValueArray=[];







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





             $typeArray  =  [

                                "select"    => "select",

                                "text"      => "text",

                                "checkbox"  => "checkbox",

                                "radio"     => "radio"

                                ];

             $service = Service::getServiceById($service_id);



             $ServiceCategory = ServiceCategory::get();

             $CustomerType    = CustomerType::get();

             $JobType         = JobType::get();



             return view('pages.admin.edit_service')

             ->with('typeArray', $typeArray)

             ->with('serviceTypeArray', $serviceTypeArray)

             ->with('serviceValueArray', $serviceValueArray)

             ->with(['service' => $service])

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

        return view('pages.admin.list_services')->with(['services' => $services,

        'db_table' => $db_table ]);
    }
}

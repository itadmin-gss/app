<?php

namespace App\Http\Controllers;

use App\Service;
use App\SpecialPrice;
use App\User;
use App\UserType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SpecialPriceController extends Controller
{

    public static function addSpecialPrice()
    {


        $special_price_add_message=FlashMessage::messages('admin.special_price_success');
        $submitted=Input::get('submitted');
        if ($submitted) {
            $rules = [
                        'service_id' => 'required',
                        'customer_id' => 'required',
                        'special_price' => 'required|numeric',
                        'status' => 'required'];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $special_price_validation_messages=General::validationErrors($validator);
                return $special_price_validation_messages;
            } else {
                $data = Input::all();
                $service=$data['service_id'];
                $customer=$data['customer_id'];
                $flag=SpecialPrice::where('service_id', '=', $service)->where('customer_id', '=', $customer)->first();
                if ($flag) {
                    $special_price_already_assigned_message=FlashMessage::messages('admin.special_price_already_error');
                    if (isset($data['type_id']) && ($data['type_id']==3)) {
                        $special_price_already_assigned_message=FlashMessage::messages('admin.special_price_already_error_vendor');
                    }

                    return FlashMessage::displayAlert($special_price_already_assigned_message, 'error');
                } else {
                    $save = SpecialPrice::addSpecialPrice($data);
                    if ($save) {
                        return FlashMessage::displayAlert($special_price_add_message, 'success');
                    }
                }
            }
        } else {
            $services=Service::getAllServices();
            $all_services=[];
            foreach ($services as $service) {
                $all_services[$service->id]=$service->title;
            }
            $user_type=UserType::where('title', '=', 'customer')->first();
            $customers=User::where('type_id', '=', $user_type->id)->get();
            $all_customers=[];

            foreach ($customers as $customer) {
                $all_customers[$customer->id]=$customer->first_name.' '.$customer->last_name.' '.$customer->company;
            }

            return view('pages.admin.addspecialprice')->with(['services' => $all_services,'customers' => $all_customers ]);
        }
    }

    public static function vendorAddSpecialPrice()
    {


        $special_price_add_message=FlashMessage::messages('admin.special_price_success');
        $submitted=Input::get('submitted');
        if ($submitted) {
            $rules = [
                        'service_id' => 'required',
                        'customer_id' => 'required',
                        'special_price' => 'required|numeric',
                        'status' => 'required'];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $special_price_validation_messages=General::validationErrors($validator);
                return $special_price_validation_messages;
            } else {
                $data = Input::all();
                $service=$data['service_id'];
                $customer=$data['customer_id'];
                $flag=SpecialPrice::where('service_id', '=', $service)->where('customer_id', '=', $customer)->first();
                if ($flag) {
                    $special_price_already_assigned_message=FlashMessage::messages('admin.special_price_already_error');
                    return FlashMessage::displayAlert($special_price_already_assigned_message, 'error');
                } else {
                    $save = SpecialPrice::addSpecialPrice($data);
                    if ($save) {
                        return FlashMessage::displayAlert($special_price_add_message, 'success');
                    }
                }
            }
        } else {
            $services=Service::getAllServices();
            $all_services=[];
            foreach ($services as $service) {
                $all_services[$service->id]=$service->title;
            }
            $user_type=UserType::where('title', '=', 'vendors')->first();
            $customers=User::where('type_id', '=', $user_type->id)->get();
            $all_customers=[];

            foreach ($customers as $customer) {
                $all_customers[$customer->id]=$customer->first_name.' '.$customer->last_name.' '.$customer->company;
            }

            return view('pages.admin.vendoraddspecialprice')->with(['services' => $all_services,'customers' => $all_customers ]);
        }
    }



    public static function editSpecialPrice($special_price_id)
    {
        $special_price_add_message=FlashMessage::messages('admin.special_price_success');
        $submitted=Input::get('submitted');
        if ($submitted) {
            $rules = [
                        'service_id' => 'required',
                        'special_price' => 'required|numeric',
                        'status' => 'required'];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $special_price_validation_messages=General::validationErrors($validator);
                return $special_price_validation_messages;
            } else {
                $data = Input::all();
                $service=$data['service_id'];
                $customer=$data['customer_id'];
                       
                    $save = SpecialPrice::updateSpecialPrice($data, $special_price_id);
                if ($save) {
                    return FlashMessage::displayAlert("Price has been modified", 'success');
                }
            }
        } else {
            $services=Service::getAllServices();
            $special_price_data=SpecialPrice::getSpecialPriceByID($special_price_id);
            $all_services=[];
            foreach ($services as $service) {
                $all_services[$service->id]=$service->title;
            }
            $user_type=UserType::where('title', '=', 'customer')->first();
            $customers=User::where('type_id', '=', $user_type->id)->get();
            $all_customers=[];

            foreach ($customers as $customer) {
                $all_customers[$customer->id]=$customer->first_name.' '.$customer->last_name;
            }

            return view('pages.admin.editspecialprice')->with(['services' => $all_services,'customers' => $all_customers,'special_price'=>$special_price_data ]);
        }
    }






    public static function editVendorSpecialPrice($special_price_id)
    {
        $special_price_add_message=FlashMessage::messages('admin.special_price_success');
        $submitted=Input::get('submitted');
        if ($submitted) {
            $rules = [
                        'service_id' => 'required',
                        'special_price' => 'required|numeric',
                        'status' => 'required'];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $special_price_validation_messages=General::validationErrors($validator);
                return $special_price_validation_messages;
            } else {
                $data = Input::all();
                $service=$data['service_id'];
                $customer=$data['customer_id'];
                $flag=SpecialPrice::where('service_id', '=', $service)->where('customer_id', '=', $customer)->first();
                if ($flag) {
                    $special_price_already_assigned_message=FlashMessage::messages('admin.special_price_already_error');
                    return FlashMessage::displayAlert($special_price_already_assigned_message, 'error');
                } else {
                    $save = SpecialPrice::updateSpecialPrice($data, $special_price_id);
                    if ($save) {
                        return FlashMessage::displayAlert($special_price_add_message, 'success');
                    }
                }
            }
        } else {
            $services=Service::getAllServices();
            $special_price_data=SpecialPrice::getSpecialPriceByID($special_price_id);
            $all_services=[];
            foreach ($services as $service) {
                $all_services[$service->id]=$service->title;
            }
            $user_type=UserType::where('title', '=', 'customer')->first();
            $customers=User::where('type_id', '=', $user_type->id)->get();
            $all_customers=[];

            foreach ($customers as $customer) {
                $all_customers[$customer->id]=$customer->first_name.' '.$customer->last_name;
            }

            return view('pages.admin.edit-vendor-special-price')->with(['services' => $all_services,'customers' => $all_customers,'special_price'=>$special_price_data ]);
        }
    }



    public static function listSpecialPrice()
    {
        $all_special_prices = SpecialPrice::getAllSpecialPrices();
                $special_price_data=[];
//                $i=0;
//                foreach($all_special_prices as $data)
//                {
//                    $special_price_data[$i]['service_code']=$data->service->service_code;
//                    $special_price_data[$i]['service_name']=$data->service->title;
//                    $special_price_data[$i]['customer_name']=$data->user->first_name.' '.$data->user->last_name;
//                    $special_price_data[$i]['price']=$data->special_price;
//                    $special_price_data[$i]['status']=$data->status;
//                    $special_price_data[$i]['created_date']=$data->created_at;
//                    $i++;
//                }
        $special_price = new SpecialPrice;
        $db_table = $special_price->getTable();
        return view('pages.admin.list_special_price')->with(['special_prices' => $all_special_prices,'db_table' => $db_table ]);
    }

    public static function vendorListSpecialPrice()
    {
        $all_special_prices = SpecialPrice::getAllVendorSpecialPrices();
            $special_price_data=[];
//                $i=0;
//                foreach($all_special_prices as $data)
//                {
//                    $special_price_data[$i]['service_code']=$data->service->service_code;
//                    $special_price_data[$i]['service_name']=$data->service->title;
//                    $special_price_data[$i]['customer_name']=$data->user->first_name.' '.$data->user->last_name;
//                    $special_price_data[$i]['price']=$data->special_price;
//                    $special_price_data[$i]['status']=$data->status;
//                    $special_price_data[$i]['created_date']=$data->created_at;
//                    $i++;
//                }
        $special_price = new SpecialPrice;
        $db_table = $special_price->getTable();
        return view('pages.admin.vendor_special_price')->with(['special_prices' => $all_special_prices,'db_table' => $db_table ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\AdditionalServiceItemImage;
use App\Asset;
use App\City;
use App\CustomerType;
use App\Helpers\FlashMessage;
use App\Helpers\SSP;
use App\Invoice;
use App\Order;
use App\OrderDetail;
use App\OrderImage;
use App\Recurring;
use App\Service;
use App\ServiceCategory;
use App\State;
use App\User;
use App\MaintenanceRequest;
use App\RequestedService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use JeroenDesloovere\Geolocation\Geolocation;


class AssetController extends Controller
{

//protected $layout = 'layouts.default';

    public function updateAssetDetails()
    {

        $asset_data = [];
        $customer_data = [];
        $customer_fields = ['customer_fname', 'customer_lname', 'customer_email', 'company'];

        foreach(Request::all() as $key => $request)
        {
            if (!in_array($key, $customer_fields))
            {
                if ($request === null)
                {
                    $asset_data[$key] = "";
                }
                else
                {
                    $asset_data[$key] = $request;
                }
            }
            else
            {
                if ($request === null)
                {
                    $customer_data[$key] = "";
                }
                else
                {
                    $customer_data[$key] = $request;
                }
            }

        }

        $asset                      = Asset::findOrFail(Request::get('property_id'));
        $customer_id                = $asset->customer_id;
        $asset->asset_number        = $asset_data['property_number'];
        $asset->property_address    = $asset_data['street_address'];
        $asset->city_id             = $asset_data['property_city'];
        $asset->state_id            = $asset_data['property_state'];
        $asset->zip                 = $asset_data['property_zip'];
        $asset->lock_box            = $asset_data['lockbox'];
        $asset->access_code         = $asset_data['access_code'];
        $asset->loan_number         = $asset_data['loan_number'];
        $asset->status              = $asset_data['status'];
        $asset->property_type       = $asset_data['type'];
        $asset->save();

        $customer = User::findOrFail($customer_id);
        $customer->first_name   = $customer_data['customer_fname'];
        $customer->last_name    = $customer_data['customer_lname'];
        $customer->email        = $customer_data['customer_email'];
        $customer->company      = $customer_data['company'];
        $customer->save();

        return "success";

    }
    public function uploadPhoto()
    {


        $file = Request::file('file');
        if ($file)
        {
            $property_id = Request::get('property_id');

            $file_ext = $file->extension();
            $file_name = date("Ymdhis").rand(100,999);
            $file->move(Config::get('app.upload_path'), $file_name.".".$file_ext);

            $asset = Asset::find($property_id);


            $asset->property_photo = $file_name.".".$file_ext;
            $asset->save();

            return json_encode(["error" => false, "file" => URL::to(Config::get('app.upload_path').$file_name.".".$file_ext)]);
        }

        throw new \Exception ("Could not upload property photo");

    }

    public function selectAvailablePhoto()
    {
        $image_url      = Request::get('image_url');
        $property_id    = Request::get('property_id');

        $string = str_replace(env('APP_URL'), "", $image_url);
        $file_parts = pathinfo(public_path().$string);
        $file_extension = $file_parts['extension'];

        $fopen      = fopen(public_path().$string, "r");
        $contents   = fread($fopen, filesize(public_path().$string));
        fclose($fopen);

        $file_name  = date("Ymdhis").rand(100,999).".".$file_extension;

        $fopen      = fopen(public_path()."/assets/uploads/".$file_name, "w");
        fwrite($fopen, $contents);
        fclose($fopen);

        if (file_exists(public_path()."/assets/uploads/".$file_name)){
            $asset = Asset::find($property_id);
            $asset->property_photo = $file_name;
            $asset->save();
            return json_encode(["error" => false]);
        }
        return json_encode(["error" => true]);

    }

    public function getAllAvailablePhotos()
    {
        $photos = [];
        $property_id = Request::get('property_id');

        //Get Maintenance Requests for Property
        $requests = MaintenanceRequest::select('id')->where("asset_id", $property_id)->get();

        foreach($requests as $request)
        {

            $request_id = $request->id;

            //Get Orders
            $orders = Order::select('id')->where('request_id', $request_id)->get();

            foreach($orders as $order)
            {

                $order_id = $order->id;

                //Get Order Images
                $order_photos = OrderImage::where('order_id', $order_id)->get();
                foreach($order_photos as $o_photo)
                {
                    switch ($o_photo->type)
                    {
                        case "after":
                            $app_path="order_additional_images_after";
                            break;

                        case "before":
                            $app_path="order_additional_images_before";
                            break;

                        case "during":
                            $app_path="order_additional_images_during";
                            break;
                    }

                    $filecheck=  config('app.'.$app_path).$o_photo->address;
                    if (file_exists($filecheck)) {
                        $photos[] = config('app.url').'/'.config('app.'.$app_path).$o_photo->address;
                    }
                }


                //Check additional items
                $addt_items = AdditionalServiceItem::where('order_id', $order_id)->get();
                foreach($addt_items as $addt)
                {
                    $addt_photos = AdditionalServiceItemImage::where('additional_service_id', $addt->id)->get();
                    foreach($addt_photos as $a_photo)
                    {
                        switch ($a_photo->type)
                        {
                            case "after":
                                $app_path="order_additional_images_after";
                                break;

                            case "before":
                                $app_path="order_additional_images_before";
                                break;

                            case "during":
                                $app_path="order_additional_images_during";
                                break;
                        }

                        $filecheck=  config('app.'.$app_path).$a_photo->address;
                        if (file_exists($filecheck)) {
                            $photos[] = config('app.url').'/'.config('app.'.$app_path).$a_photo->address;
                        }
                    }
                }
            }
        }

        return json_encode($photos);


    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        //Get Asset Information
        $property_details =  Asset::where('id', $id)->get();

        if (count($property_details) < 1)
        {
            throw new \Exception ("Could not find Asset");
            exit;
        }

        //Get Customer Information
        $customer_details = User::where('id', $property_details[0]->customer_id)->get();


        //Get City/State Information
        $city = City::where('id', $property_details[0]->city_id)->get()[0]->name;
        $state = State::where('id', $property_details[0]->state_id)->get()[0]->name;

        $geolocation_result = (new Geolocation)->getCoordinates($property_details[0]->property_address, '', $city, $property_details[0]->zip, 'USA');


        //Get Requests
        $requests = MaintenanceRequest::where('asset_id', $property_details[0]->id)->get();

        //Get Order Details / Request Details
        $orderDetails = [];
        $vendorsFound = [];
        $inv_orders = [];
        $invoices = [];
        foreach($requests as $request)
        {
            $orderDetails[$request->id]['status'] = $request->status;
            $data = Order::where('request_id', $request->id)->get();
            foreach($data as $dt)
            {
                $od = OrderDetail::where('order_id', $dt->id)->get();
                $inv = Invoice::where('order_id', $dt->id)->get();
                if ($inv && count($inv) > 0)
                {
                    $invoices[$dt->id] = $inv;
                }
                if (count($od) > 0)
                {
                    $od = $od[0];
                    $inv_orders[$dt->id] = $od;
                }
                else
                {
                    break;
                }

                $vendor_details = User::where('id', $dt->vendor_id)->get();


                if (count($vendor_details) > 0)
                {
                    $vendorsFound[$vendor_details[0]->id] = $vendor_details;
                    $vendor_name = "";
                    if (isset($vendor_details[0]->first_name)){
                        $vendor_name .= $vendor_details[0]->first_name;
                    }

                    if (isset($vendor_details[0]->last_name))
                    {
                        $vendor_name .= " ".$vendor_details[0]->last_name;
                    }
                    $orderDetails[$request->id]["order_details"][$dt->id]["vendor_name"] = trim($vendor_name);
                    $orderDetails[$request->id]["order_details"][$dt->id]["vendor_company"] = $vendor_details[0]->company;
                }

                $orderDetails[$request->id]["order_details"][$dt->id]["status"] = $dt->status_text;
                $orderDetails[$request->id]["order_details"][$dt->id]["completed"] = $od->completion_date;
                $orderDetails[$request->id]["order_details"][$dt->id]["approved"] = $od->approved_date;
                $orderDetails[$request->id]["order_details"][$dt->id]["created"] = $od->created_at;
                $svc = RequestedService::where('id', $od->requested_service_id)->get();
                foreach($svc as $service)
                {
                    $service_details = Service::where('id', $service->service_id)->get();
                    if (isset($service_details[0]))
                    {
                        $orderDetails[$request->id]["order_details"][$dt->id]['requested_services'][] = $service_details[0];
                    }
                }
            }

        }

        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();



        return view('pages.admin.asset-details')
            ->with('property_details', $property_details[0])
            ->with('invoices', $invoices)
            ->with('inv_orders', $inv_orders)
            ->with('geolocation', $geolocation_result)
            ->with('city', $city)
            ->with('state', $state)
            ->with('cities', $cities)
            ->with('states', $states)
            ->with('customer_info', $customer_details[0])
            ->with('order_details', $orderDetails)
            ->with('vendors_found', $vendorsFound)
            ->with('requests', $requests);
    }

    public function showAddAsset()
    {
        //if user is logged in send to page to add assets
        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();
        return view('pages.add_assets')
        ->with('cities', $cities)
        ->with('states', $states);
    }

    public function showAddAssetNew($id = 0)
    {
        if ($id==1) {
            Session::put('request_addition', "add-new-service-request");
            Session::put('request_flag', 1);
        }
        $customerType=CustomerType::get();
        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();
        return view('pages.customer.add_assets_customer')
        ->with('cities', $cities)
        ->with('states', $states)
        ->with('customerType', $customerType);
    }

    public function editAsset($id)
    {

        if (Auth::check()) {
            $method = Request::method();
            //if form is submitted
            if ($method == 'POST') {
                $data = Request::all();
                if ($data['edit_asset'] == 'yes') {
                    //Set rules for validation of assets
                    $rules = Asset::requiredFields(1); //These rules are defined in Asset Model
                    $validator = Validator::make(Request::all(), $rules); // put all rules to validator
                    // if validation is failed redirect to add customer asset with errors
                    if ($validator->fails()) {
                        return redirect('edit-customer-asset/' . $id)
                        ->withErrors($validator)
                                        ->withInput(Request::except('password')); // send back all errors to the login form
                        ; // send back the input (not the password) so that we can repopulate the form
                    } else {
                        // Get all form data in $data variable
                        $data = Request::all();
                        $message = '';
                        $street = '';
                        $streetNumber = '';
                        $city_id = Request::get('city_id');
                        $city = City::find($city_id)->name;
                        $zip = Request::get('zipcode');

                        $property_address= Request::get('property_address');
                        $state_id = Request::get('state_id');
                        $state = State::find($state_id)->name;
                        $country = 'USA';
                        $result  =  Asset::getLatLong($property_address.$zip, $city, $state);
                        $data['latitude'] = $result['lat'];
                        $data['longitude'] = $result['lng'];
                        $edit = Asset::editAsset($data, $id);

                        if ($edit) {
                            Session::flash('message', 'Asset Updated successfully.');
                        }
                    }
                }
            }
            $customerType=CustomerType::get();
            $asset_data = Asset::find($id);
            //Get all cities from city
            $cities = City::getAllCities();
            //Get all states from city
            $states = State::getAllStates();
            return view('pages.customer.edit_assets')
            ->with('cities', $cities)
            ->with('states', $states)
            ->with('customerType', $customerType)
            ->with('asset_data', $asset_data);
        } else {
            return redirect('/'); // else return to login page
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createAsset()
    {
        $id = Auth::user()->id;
        $input_data = Request::all();
        $one_column = $input_data['one_column'];
        if ($one_column == 'yes') {
            $page_redirect = 'add-customer-asset';
            $succes_redirect = 'customer';
        } else {
            $message = FlashMessage::messages('customer.add_new_asset_success');
            $page_redirect = 'add-new-customer-asset';
            $succes_redirect = 'add-new-customer-asset';
        }
        //Set rules for validation of assets
        if (Request::get('outbuilding_shed')==0 || Request::get('outbuilding_shed')=="") {
            //outbuilding_shed notes flag
            $rules =  [
                'asset_number' => 'required|unique:assets',
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

                'special_direction_note' => '',
                'utility_note' => '',
                'swimming_pool',
                'occupancy_status'=>'required'
                ];
        } else {
            $rules = Asset::requiredFields();
        }



        $validator = Validator::make(Request::all(), $rules); // put all rules to validator
        // if validation is failed redirect to add customer asset with errors
        if ($validator->fails()) {
            return redirect($page_redirect)
                            ->withErrors($validator)// send back all errors to the login form
                            ->withInput(Request::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            $message = '';
            $street = '';
            $streetNumber = '';
            $city_id = Request::get('city_id');
            $city = City::find($city_id)->name;
            $zip = Request::get('zipcode');
            $country = 'United States';
            $property_address= Request::get('property_address');
            $state_id = Request::get('state_id');
            $state = State::find($state_id)->name;
            $country = 'USA';
            $result  =  Asset::getLatLong($property_address.$zip, $city, $state);

           // Get all form data in $data variable
            $data = Request::all();
            $data['latitude'] = $result['lat'];
            $data['longitude'] = $result['lng'];
            //set data in addAsset function
            $save = Asset::addAsset($data, $id);
        }
        // if data inserted successfully
        if ($save) {
            $message="Property has been added!";
            Session::flash('message', $message);


            if (Session::get('request_flag')==1) {
                return redirect(Session::get('request_addition'));
                Session::put('request_addition', "");
                Session::put('request_flag', "");
            } else {
                return redirect('view-assets-list');
            }
        } else {
            return redirect($page_redirect); // return back to add customer asset page
        }

        //
    }

    public static function deleteAsset($id)
    {

        $delete = Asset::where('id', '=', $id)->update(['status'=>0]);
        return Redirect::back();
    }
    public static function deleteSelectedAsset($id)
    {
        // $delete = Asset::deleteAsset($id);
         $deletedAsset = Asset::where('id', '=', $id)->delete();
    }
    public static function viewAssetsList()
    {
        //Call viewAssets function
        $assets_data = Asset::getAssetsByCustomerId(Auth::user()->id);
        return view('pages.view_assets')->with('assets_data', $assets_data);  // set assets_data to view to show list of assets
    }


    public function addAdminAsset($id = 0)
    {

        if ($id==1) {
            Session::put('request_addition', "admin-add-new-service-request");
            Session::put('request_flag', 1);
        }

        $customer = User::getAllCustomers();
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();
        $submitted = Request::get('submitted');
        $customerType=CustomerType::get();

        if ($submitted) {
            if (Request::get('outbuilding_shed')==0 || Request::get('outbuilding_shed')=="") {
                //outbuilding_shed notes flag
                $rules =  [
                'asset_number' => 'required|unique:assets',
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
                'lock_box' => (Request::get('occupancy_status'))=="Occupied" ? '': 'required',
                'access_code' => '',
                'brokage' => 'required',
                'agent' => 'required',
                'customer_email_address' => '',
                'outbuilding_shed' => 'required',

                'special_direction_note' => '',
                'utility_note' => '',
                'swimming_pool',
                'occupancy_status'=>'required'
                   ];
            } else {
                $rules = [
                'asset_number' =>  'required',
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
                'lock_box' => (Request::get('occupancy_status'))=="Occupied" ? '': 'required',
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
            }
            $rules['customer_id'] = 'required';
            $validator = Validator::make(Request::all(), $rules);
            //print_r($validator); exit;
            if ($validator->fails()) {
                 return Redirect::back()
                 ->withErrors($validator)
                 ->withInput(Request::except('password'));
            } else {
                $data = Request::all();
                $customer_id = $data['customer_id'];
                $message = '';

                $city_id = Request::get('city_id');
                $city = City::find($city_id)->name;
                $zip = Request::get('zipcode');
                $property_address= Request::get('property_address');
                $state_id = Request::get('state_id');
                $state = State::find($state_id)->name;
                $country = 'USA';
                $result  =  Asset::getLatLong($property_address.$zip, $city, $state);
                $data['latitude'] = $result['lat'];
                $data['longitude'] = $result['lng'];
                $save = Asset::addAsset($data, $customer_id);
            }

            if ($save) {
                if (Session::get('request_flag')==1) {
                      return redirect(Session::get('request_addition'))
                      ->with('message', FlashMessage::displayAlert('Your property has been added Successfully', 'success'));
                      Session::put('request_addition', "");
                      Session::put('request_flag', "");
                } else {
                    return redirect('list-assets')
                    ->with('message', FlashMessage::displayAlert('Your property has been added Successfully', 'success'));
                }
            } else {
                return view('pages.admin.add_assets')
                ->with('cities', $cities)
                ->with('states', $states)
                ->with('customerType', $customerType);
            }
        } else {
            return view('pages.admin.add_assets')
            ->with('cities', $cities)
            ->with('states', $states)
            ->with('customerType', $customerType);
        }

        /* if ($save) {
          return redirect('list-asset');
          } else {
          return view('pages.admin.add_assets');
      } */
    }

    public function listAdminAssets()
    {
        $assets = Asset::get();
        return view('pages.admin.list_assets')
        ->with([
        'assets_data' => $assets]);
    }

    public function summaryWorkOrderTable()
    {
        //Database Table
        $table = 'orders';

        //DB Primary key
        $primaryKey = 'id';

        $columns = array(
            array(
                'db' => 'request_id',
                'dt' => 0,
                'formatter' => function($d, $row)
                {
                    $request_details = MaintenanceRequest::where('id', $d)->get()[0];
                    $asset_id        = $request_details->asset_id;
                    $asset_details   = Asset::where('id', $asset_id)->get()[0];
                    $customer_details = User::where('id', $asset_details->customer_id)->get()[0];
                    return [$asset_id, $asset_details->property_address, $d, $customer_details->first_name." ".$customer_details->last_name];

                }
            ),
            array(
                'db' => 'vendor_id',
                'dt' => 4,
                'formatter' => function($d, $row)
                {
                    $vendor_details = User::where('id', $d)->get()[0];
                    $vendor_name = "";
                    if (isset($vendor_details->first_name))
                    {
                        $vendor_name .= $vendor_details->first_name;
                    }

                    
                }
            )
        );

        $sql_details = array(
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST')
        );

        return SSP::simple(Request::all(), $sql_details, $table, $primaryKey, $columns);

    }
    public function propHistoryTable()
    {
        //Database Table
        $table = 'maintenance_requests';

        //DB Primary Key
        $primaryKey = 'id';

        $columns = array(
            
            //Fake Out script to pull more fields
            array('db' => 'customer_id', 'dt' => 14),
            array('db' => 'asset_id', 'dt' => 15),
            

            //Row 0 - Customer Information / Customer Type / Property Address
            
            array(
                'db' => 'id',
                'dt' => 0,
                'formatter' => function($d, $row)
                {
                    //Get Asset Information or Request
                    $asset_details = Asset::where('id', $row['asset_id'])->get()[0];

                    //Get Requested Services
                    $requested_services = RequestedService::where('request_id', $d)->get();

                    //Get City Information
                    $city = City::where('id', $asset_details->city_id)->get(['name'])[0]->name;

                    //get State Information
                    $state = State::where('id', $asset_details->state_id)->get(['name'])[0]->name;

                    //get Order information
                    $order_details = Order::where('request_id', $d)->get();

                    if (isset($order_details[0]->id))
                    {
                        $order_id = $order_details[0]->id;
                        $order_status_text = $order_details[0]->status_text;
                        $order_status_color = $order_details[0]->status_class;
                        $order_completion_date = $order_details[0]->completion_date;
                        $vendor_details = User::where('id', $order_details[0]->vendor_id)->get()[0];
                        $vendor_name = "";
                        if (isset($vendor_details->first_name))
                        {
                            $vendor_name .= $vendor_details->first_name." ";
                        }
                        if (isset($vendor_details->last_name))
                        {
                            $vendor_name .= $vendor_details->last_name;
                        }
                        
                    }
                    else
                    {
                        $order_id = "NA";
                        $order_status_text = "NA";
                        $order_status_color = "white";
                        $order_completion_date = "NA";
                        $vendor_name = "NA";
                    }

                    //Put together Services column
                    $services = "";
                    foreach($requested_services as $r_service)
                    {
                        $service_title = Service::where('id', $r_service->service_id)->get()[0]->title;
                        $services .= $service_title;
                        $services .= "<br>".$r_service->due_date."<br>";
                    }

                    //Property Address
                    $property_address = $asset_details->property_address;

                    //Unit 
                    $unit = $asset_details->UNIT;

                    //Zip
                    $zip = $asset_details->zip;
                    
                    //Get Customer Type
                    if (isset($asset_details->customer_type))
                    {
                        $customer_type = CustomerType::where('id', $asset_details->customer_type)->get(['title'])[0]->title;
                    }
                    else
                    {
                        $customer_type = "Not Set";
                    }
                    //Get Customer Information
                    if (isset($asset_details->customer_id))
                    {
                        $customer_details = User::where('id', $asset_details->customer_id)->get()[0];
                        $customer_name = "";
                        if (isset($customer_details->first_name))
                        {
                            $customer_name .= $customer_details->first_name." ";
                        }

                        if (isset($customer_details->last_name))
                        {
                            $customer_name .= $customer_details->last_name;
                        }

                        
                    } 
                    else
                    {
                        $customer_name = "";
                    }

                    return [
                        $customer_type, 
                        trim($customer_name), 
                        $property_address, 
                        $unit, 
                        $city, 
                        $state, 
                        $zip, 
                        $services, 
                        $order_id, 
                        $order_status_text, 
                        $order_status_color, 
                        $order_completion_date,
                        $vendor_name
                    ];
                }
            ),



        );

        $sql_details = array(
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST')
        );

        return SSP::simple(Request::all(), $sql_details, $table, $primaryKey, $columns);
    }

    public function listAdminAssetsSummary()
    {
    
        return view('pages.admin.list-assets-summary');
    }
    public function propertyReport()
    {



           $work_orders = Order::get();


           $list_orders = [];
           $i = 0;






           $assets = Asset::orderBy('id', 'desc')->get();

           $serviceCategories=ServiceCategory::get();
           $service=Service::where('status', '=', 1)->where('bid_flag', '=', 0)->get();


           return view('pages.admin.property-report')
           ->with([
        'assets_data' => $assets,
        'list_orders'=>$work_orders,
        'serviceCategories'=>$serviceCategories,
        'services'=>$service
            ]);
    }



    public function recurringReport()
    {



           $work_orders = Recurring::get();


           $list_orders = [];
           $i = 0;






           $assets = Asset::orderBy('id', 'desc')->get();

           $serviceCategories=ServiceCategory::get();
           $service=Service::where('status', '=', 1)->where('bid_flag', '=', 0)->get();


           return view('pages.admin.recurring-report')
           ->with([
        'assets_data' => $assets,
        'list_orders'=>$work_orders,
        'serviceCategories'=>$serviceCategories,
        'services'=>$service
            ]);
    }
    public function reporting()
    {

         $data = Request::all();

        if (isset($data['order_staus']) && $data['order_staus'] !="") {
              $work_orders = Order::where('status', '=', $data['order_staus'])->get();
        } elseif (isset($data['properties']) && $data['properties'] !="") {
               $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                   $data = Request::all();
                   $q->where('asset_id', '=', $data['properties']);
               })

               ->get();
        } elseif (isset($data['service_type']) && $data['service_type'] !="") {
            $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                $data = Request::all();

                $q->whereHas('requestedService', function ($qq) {
                    $data = Request::all();

                    $qq->where('service_id', '=', $data['service_type']);
                });
            }) ->get();
        } else if ((isset($data['properties']) && isset($data['order_staus'])) && ($data['order_staus']!="" && $data['properties'] !="" )) {
            $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                $data = Request::all();
                $q->where('asset_id', '=', $data['properties']);
            })->where('status', '=', $data['order_staus'])->get();
        } else {
            $work_orders = Order::get();
        }

        $list_orders = [];
        $i = 0;


        foreach ($work_orders as $order) {
              $order_details = ($order->orderDetail);
             //Property Address, City, State, Zip fields
              $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                   $customerlastname=$order->customer->last_name;
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                   $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                   $vendorlastname=$order->vendor->last_name;
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                  $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                   $jobtype = "";
            }



            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['job_type'] = $jobtype;
            if (isset($order->maintenanceRequest->asset->asset_number)) {
                 $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            } else {
                  $list_orders[$i]['asset_number'] = "";
            }


            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['due_date'] = '';
            if (isset($order->maintenanceRequest->asset->property_address)) {
                  $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            } else {
                  $list_orders[$i]['property_address'] = "";
            }

            if (isset($order->maintenanceRequest->asset->city->name)) {
                  $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                  $list_orders[$i]['city'] = "";
            }
            if (isset($order->maintenanceRequest->asset->state->name)) {
                  $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            } else {
                  $list_orders[$i]['state'] = "";
            }

            if (isset($order->maintenanceRequest->asset->UNIT)) {
                 $list_orders[$i]['unit'] = $order->maintenanceRequest->asset->UNIT;
            } else {
                  $list_orders[$i]['unit'] ="" ;
            }


            if (isset($order->maintenanceRequest->asset->zip)) {
                  $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            } else {
                 $list_orders[$i]['zipcode'] = "";
            }


            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['completion_date']=$order->completion_date;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;

            $list_orders[$i]['submit_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submit_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                    $list_orders[$i]['due_date'] .=$order_detail->requestedService->due_date."<br/>"  ;
                } else {
                    $list_orders[$i]['due_date'].="Not Set"."<br/>";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title .', <br>';
                }
            }
            $i++;
        }
















        $assets = Asset::orderBy('id', 'desc')->get();

        $serviceCategories=ServiceCategory::get();
        $service=Service::where('status', '=', 1)->where('bid_flag', '=', 0)->get();


        return view('pages.admin.reporting')
        ->with([
        'assets_data' => $assets,
        'orders'=>$list_orders,
        'serviceCategories'=>$serviceCategories,
        'services'=>$service,
        'data'=>$data
        ]);
    }


    public function whiteboardReporting()
    {

         $data = Request::all();

        if (isset($data['order_staus']) && $data['order_staus'] !="") {
              $work_orders = Order::where('status', '=', $data['order_staus'])->get();
        } elseif (isset($data['properties']) && $data['properties'] !="") {
               $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                   $data = Request::all();
                   $q->where('asset_id', '=', $data['properties']);
               })

               ->get();
        } elseif (!empty($data['service_type']) && count($data['service_type']) >0) {
            $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                $data = Request::all();

                $q->whereHas('requestedService', function ($qq) {
                    $data = Request::all();

                    $qq->whereIn('service_id', $data['service_type']);
                });
            }) ->get();
        } else if ((isset($data['properties']) && isset($data['order_staus'])) && ($data['order_staus']!="" && $data['properties'] !="" )) {
            $work_orders = Order::whereHas('maintenanceRequest', function ($q) {
                $data = Request::all();
                $q->where('asset_id', '=', $data['properties']);
            })->where('status', '=', $data['order_staus'])->get();
        } else {
            $work_orders = Order::get();
        }

        $list_orders = [];
        $i = 0;


        foreach ($work_orders as $order) {
              $order_details = ($order->orderDetail);
             //Property Address, City, State, Zip fields
              $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                   $customerlastname=$order->customer->last_name;
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                   $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                   $vendorlastname=$order->vendor->last_name;
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                  $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                   $jobtype = "";
            }



            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['job_type'] = $jobtype;
            if (isset($order->maintenanceRequest->asset->asset_number)) {
                  $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            } else {
                  $list_orders[$i]['asset_number'] = "";
            }


            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['due_date'] = '';
            if (isset($order->maintenanceRequest->asset->property_address)) {
                  $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            } else {
                  $list_orders[$i]['property_address'] ="";
            }


            if (isset($order->maintenanceRequest->asset->customerType->title)) {
                $list_orders[$i]['clientType'] = $order->maintenanceRequest->asset->customerType->title;
            } else {
                $list_orders[$i]['clientType'] = "";
            }
            if (isset($order->maintenanceRequest->asset->city->name)) {
                  $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                 $list_orders[$i]['city'] = "";
            }

            if (isset($order->maintenanceRequest->asset->state->name)) {
                  $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            } else {
                  $list_orders[$i]['state'] = "";
            }
            if (isset($order->maintenanceRequest->asset->UNIT)) {
                 $list_orders[$i]['unit'] = $order->maintenanceRequest->asset->UNIT;
            } else {
                $list_orders[$i]['unit'] = "";
            }


            if (isset($order->maintenanceRequest->asset->zip)) {
                 $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            } else {
                 $list_orders[$i]['zipcode'] = "";
            }


            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['completion_date']=$order->completion_date;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;

            $list_orders[$i]['submit_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submit_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                    $list_orders[$i]['due_date'] .=$order_detail->requestedService->due_date."<br/>"  ;
                } else {
                    $list_orders[$i]['due_date'].="Not Set"."<br/>";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title .' <br>';
                }
            }
            $i++;
        }

        $assets = Asset::orderBy('id', 'desc')->get();

        $serviceCategories=ServiceCategory::get();
        $service=Service::where('status', '=', 1)->where('bid_flag', '=', 0)->get();


        return view('pages.admin.whiteboard-reporting')
        ->with([
        'assets_data' => $assets,
        'orders'=>$list_orders,
        'serviceCategories'=>$serviceCategories,
        'services'=>$service,
        'data'=>$data
        ]);
    }

    public function editAdminAsset($asset_id)
    {
        $submitted = Request::get('submitted');

        $customerType=CustomerType::get();
        if ($submitted) {
            $data = Request::all();


            $message = '';

            $city_id = Request::get('city_id');
            $city = City::find($city_id)->name;
            $zip = Request::get('zipcode');
            $property_address= Request::get('property_address');
            $state_id = Request::get('state_id');
            $state = State::find($state_id)->name;
            $country = 'USA';
            $result  =  Asset::getLatLong($property_address.$zip, $city, $state);
            $data['latitude'] = $result['lat'];
            $data['longitude'] = $result['lng'];

            $property_dead_status=0;
            if (Request::get('property_status')=='closed' || Request::get('property_status')=='inactive') {
                $property_dead_status=1;

                $data['property_dead_date']=date('m/d/Y h:i:s A') ;
                $data['property_dead_user']=Auth::user()->id;
            }

            $data['property_dead_status']=$property_dead_status;


            $save = Asset::editAsset($data, $asset_id);

            if ($save) {
                  $message = FlashMessage::messages('admin_asset.asset_updated');
                  return redirect('list-assets')
                  ->with('message', FlashMessage::displayAlert($message, 'success'));
            } else {
                return view('pages.admin.add_assets');
            }
        } else {
            $asset = Asset::find($asset_id);
            //Get all cities from city
            $cities = City::getAllCities();
            //Get all states from city
            $states = State::getAllStates();
            //Get all customers
            $customers = User::getAllCustomers();
            return view('pages.admin.edit_assets')
            ->with([
            'asset_data' => $asset,
            'states' => $states,
            'cities' => $cities,
            'customers' => $customers,
            'customerType' => $customerType,
            ]);
        }
    }

    //Get Asset map
    public function getAssetMap()
    {

          $zip = Request::get('zip');
          $state = Request::get('state');
          $city = Request::get('city');
          $property_address= Request::get('property_address');
        if ($state=="Select State") {
             $state="";
        }
        if ($city=="Select City") {
             $city="";
        }
        $country = 'USA';
             // $result = Geolocation::getCoordinates($property_address, ' ', $city, $zip, $country);
        $myresult =     Asset::getLatLong($property_address, $zip, $city, $state);


        /*
        ->with('zipcode',$myresult['overall']->results[0]->address_components[0]->long_name)
            ->with('City',$myresult['overall']->results[0]->address_components[1]->long_name)
            */
            $zipcode="";
            $state="";
            $city="";

        if (!empty($myresult['overall']->results)) {
            foreach ($myresult['overall']->results[0]->address_components as $addrescomp) {
                if ($addrescomp->types[0]=="postal_code") {
                    $zipcode = $addrescomp->long_name;
                }
                if ($addrescomp->types[0]=="locality") {
                      $city = $addrescomp->long_name;
                }
                if ($addrescomp->types[0]=="administrative_area_level_1") {
                      $state = $addrescomp->long_name;
                }
            }
            return  view('pages.admin.add-asset-map')
            ->with('latitude', $myresult['lat'])
            ->with('longitude', $myresult['lng'])
            ->with('zipcode', $zipcode)
            ->with('city', $city)
            ->with('state', $state);
        } else {
            return " ";
        }
    }
}

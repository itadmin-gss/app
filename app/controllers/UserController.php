<?php

/**
 * User Controller Class.
 *
 * Handles User Functionalities & redirects.
 * @copyright Copyright 2014 Devronix Technology Development Team
 * @version $Id: 1.0
 */
use Cryt\Forms\RegistrationForm;
use JeroenDesloovere\Geolocation\Geolocation;

class UserController extends \BaseController {

    /**
     * Redirects the Users to Login Page
     * @params none
     * @return redirects User to Login Page.
     */
    public function showLogin() {

        if (Auth::check()) {
            $user = Auth::user();
            $redirect = $this->whereRedirect($user->id);
            return $redirect;
        } else {
            // show the form
            return View::make('home');
        }
    }

    /**
     * Redirects the Users to Registration Page
     * @params none
     * @return redirects User to Registration Page.
     */
    public function showRegistration() {


        $customer = DB::table('user_types')->where('title', 'customer')->pluck('id');
        $vendor = DB::table('user_types')->where('title', 'vendors')->pluck('id');

        return View::make('pages.customer.registration')->with('customer', $customer)->with('vendor', $vendor);
    }

    public function showThankYou($user_id) {
        $user = User::find($user_id);
        return View::make('pages.thankyou')->with('user', $user);
    }

    /**
     * Create User with regisetration form process
     * @params none
     * @return Redirect to login page with success message & send an email to User.
     */
    public function createUser() {

        $rules = array(
            'first_name' => 'required|min:2|max:80|alpha',
            'last_name' => 'required|min:2|max:80|alpha',
            'email' => 'required|email|unique:users|between:3,64',
            'username' => 'required|unique:users',
            'password' => 'required|between:4,20|confirmed',
            'password_confirmation' => 'same:password',
            'type_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {

            return Redirect::to('user-register')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {


            $data = Input::all();
            $user_type = Input::get('type_id');
            unset($data['_token'], $data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 0;

            $data['type_id'] = $data['type_id'] ? $data['type_id'] : NULL;
            $user_types = UserType::find($user_type);
            $user_roles = UserRole::where('role_name', '=', $user_types->title)->first();
            $data['user_role_id'] = $user_roles->id;
            $created_user_id = User::createUser($data);

            if ($created_user_id) {
                $id = $created_user_id;

                $email_data = array(
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'username' => Input::get('username'),
                    'email' => Input::get('email'),
                    'id' => $id,
                    'user_email_template'=>EmailNotification::$user_email_template
                );

                 $customervendor="";
                 $notification_url="";
                if(    $user_type == 3)
                {
                    $customervendor="Vendor";
                    $notification_url="list-vendors";
                }
                else
                {
                      $customervendor="Customer";
                     $notification_url="list-customer";
                }
                // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
                foreach( $recepient_id as $rec_id)
                {
                    //admin to admin notification
                $notification = NotificationController::doNotification($rec_id,$rec_id, "New ".$customervendor." ".$id." has been registered.", 1,$email_data,$notification_url);
                }

                

                Email::send(Input::get('email'), 'Welcome to GSS', 'emails.customer_registered', $email_data);
               
                return Redirect::to('thankyou/' . $id);
            }
        }
    }

    
    function activeUser($id) {
        if (Auth::check()) {
            $user = Auth::user();
            $userController = new UserController;
            $redirect = $userController->whereRedirect($user->id);
            $userdata = array('status' => 1 );
            $save = User::find($id)->update($userdata);
            return $redirect;
        } else {
            // show the form
            $userdata = array('status' => 1 );
            $save = User::find($id)->update($userdata);
            return View::make('home')->with('active', $id);
        }
        
    }
    
    /**
     * Handle login process & Authenticate user
     * @params none
     * @return Redirect to dashboard or profile complete form on the basis of user's profile status & user status.
     */
    public function doLogin() {



        $rules = array(
            'username' => 'required',
            'password' => 'required|between:4,20'
        );



        $validator = Validator::make(Input::all(), $rules);

        $field = filter_var(Input::get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $username = Input::get('username');
        $password = Input::get('password');
		
        if ($validator->fails()) {
			
            $messages = $validator->messages();

            return Redirect::to('/')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {

			
            $status = User::where($field,'=',$username)->first();
			
            if(isset($status))
            {
            if($status->status == 1)
            {
				//$field = "username";
                $userdata = array(
                $field => trim($username),
                'password' => trim($password),
                'status' => 1
                );

                if (Input::get('remember_me')) {
                    $auth_attempt = Auth::attempt($userdata, true);
                } else {
                    $auth_attempt = Auth::attempt($userdata);
                }
				//echo "<pre>";print_r($auth_attempt);print_r($userdata);exit;
                if ($auth_attempt) {

                    $user = Auth::user();
                    $redirect = $this->whereRedirect($user->id);
                    return $redirect;
                } else {
                    $login_error_message = FlashMessage::messages('user.user_login_error');
                    return Redirect::back()
                                    ->withErrors(array('password' => 'Invalid Login. Please correct your user name or password'));
                }
            }
            else
            {
                return Redirect::back()
                                    ->withErrors(array('password' => 'Your account is De-Activated kindly contact to admin to activate your account'));
            }
        }
        else
        {
                $login_error_message = FlashMessage::messages('user.user_login_error');
                    return Redirect::back()
                                    ->withErrors(array('password' => 'Invalid Login. Please correct your user name or password'));
                
        }


            
        }
    }



     /**
     * Handle login process & Authenticate user
     * @params none
     * @return Redirect to dashboard or profile complete form on the basis of user's profile status & user status.
     */
    public function doLoginAsUser($userid) {
        Auth::loginUsingId($userid);
         return Redirect::to('/');
    }

    /**
     * Get use to profile edit page
     * @params none
     * @return Redirect to profile edit page.
     */
    public function editProfile() {
        $user_data = Auth::user();
        $user_type = UserType::getUserTypeByID($user_data->type_id);
        $clientType=CustomerType::get();
         $vendor_services = VendorService::getAllVendorServices();
           $VendorServiceArray=array();
        foreach ($vendor_services as $value) {
          $VendorServiceArray[]=$value->service_id;
        }

        $servicesDATAoption='';
        foreach ($clientType as  $clientTypeData) {
                 $servicesDATAoption.="<optgroup label='".$clientTypeData->title."'>";
                 $getserviceBycustomerType=Service::where("customer_type_id","=",$clientTypeData->id)->get();
                 foreach ($getserviceBycustomerType as $getserviceBycustomerTypeDATA) {
                 
                      if( in_array($getserviceBycustomerTypeDATA->id, $VendorServiceArray) ) 
             {
             
            $servicesDATAoption.="<option value='".$getserviceBycustomerTypeDATA->id."' selected=\"selected\">".$getserviceBycustomerTypeDATA->title."</option>";
              
                
              }
              else
              {
                $servicesDATAoption.="<option value='".$getserviceBycustomerTypeDATA->id."' >".$getserviceBycustomerTypeDATA->title."</option>";
            
              }


                         
                 }



                 $servicesDATAoption.="</optgroup>";
         
        }
       


       
        $cities = City::getAllCities();
        $states = State::getAllStates();
        
         $services=   Service::getAllServices();


      

        $CustomerType=CustomerType::get();
        return View::make('common.edit_profile')
        ->with('cities', $cities)
        ->with('states', $states)
        ->with('user_data', $user_data)
        ->with('user_type', $user_type)
        ->with('services',$services)
        ->with('vendor_services',$VendorServiceArray)
        ->with('CustomerType',$CustomerType)
        ->with('servicesDATAoption',$servicesDATAoption);
    }

    /**
     * Process edit profile data & update database accordingly.
     * @params none
     * @return return success & error message through AJAX.
     */
    public function saveProfile() {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $username = Auth::user()->username;
            Validator::extend('hashmatch', function($attribute, $value, $parameters) {
                return Hash::check($value, Auth::user()->$parameters[0]);
            });

            $messages = array(
                'hashmatch' => 'Your current password must match your account password.'
            );

            if (Input::get('change_password')) {
                $rules = array(
                    'first_name' => 'required|min:2|max:80|alpha',
                    'last_name' => 'required|min:2|max:80|alpha',
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    'current_password' => 'hashmatch:password',
                    'password' => 'required|between:4,20|confirmed',
                    'password_confirmation' => 'same:password',
                );
            } else {
                $rules = array(
                    'first_name' => 'required|min:2|max:80|alpha',
                    'last_name' => 'required|min:2|max:80|alpha',
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                );
            }
            if (Input::get('check_user_name') == 'yes') {
                $rules['username'] = 'required|unique:users';
            }
            $validator = Validator::make(Input::all(), $rules, $messages);

            if ($validator->fails()) {
                $validation_messages = $validator->messages()->all();
                $profile_error_messages = '';
                foreach ($validation_messages as $validation_message) {
                    $profile_error_messages.="<h4 class='alert alert-error'>" . $validation_message . "</h4>";
                }
                return $profile_error_messages;
            } else {

                $street = '';
                $streetNumber = '';
                $city_id = Input::get('city_id');
                $city = City::find($city_id)->name;
                $zip = Input::get('zipcode');
                $country = 'United States';
                $result = Geolocation::getCoordinates($street, $streetNumber, $city, $zip, $country);


                $profile_message = FlashMessage::messages('vendor.profile_edit_success');
                $data = Input::all();
                $data['latitude'] = $result['latitude'];
                $data['longitude'] = $result['longitude'];
                if (!Input::get('change_password')) {
                    $data['password'] = Auth::user()->password;
                } else {
                    $data['password'] = Hash::make($data['password']);
                }
                $file = Input::file('profile_picture');
                if ($file) {
                    $destinationPath = Config::get('app.upload_path');
                    $filename = $file->getClientOriginalName();
                    $filename = str_replace('.', '-' . $username . '.', 'profile-' . $filename);
                    $data['profile_picture'] = $filename;
                    Input::file('profile_picture')->move($destinationPath, $filename);
                } else {
                    $data['profile_picture'] = Auth::user()->profile_picture;
                }
                $save = User::profile($data, $id);
   
                $affectedRows = VendorService::where('vendor_id', '=', Auth::user()->id)->delete();
                foreach ($data['vendor_services'] as  $value) {
                    $dataArray['vendor_id']=Auth::user()->id;
                    $dataArray['status']=1;
                    $dataArray['service_id']=$value;

                 VendorService::create($dataArray);  
                }


                if ($save) {
                    return FlashMessage::displayAlert($profile_message, 'success');
                }
            }
        } else {
            return Redirect::to('/');
        }
    }

    /**
     * Check where to redirect user according to their types.
     * @params User ID
     * @return Redirect to relative pages.
     */
    public function whereRedirect($id) {
        $user = User::find($id);
        $type_id = $user->type_id;
        $profile_status = User::getProfileStatusById($id);
        $user_status = User::getUserStatusById($id);
        $user_type = UserType::getUserTypeByID($type_id);



        if ($user_type == 'vendors') {

            if ($profile_status == 0 && $user_status == 1) {

                $redirect = 'vendor-profile-complete';
                return Redirect::to($redirect);
            } else if ($profile_status == 1 && $user_status == 1) {

                $redirect = $user_type;
                return Redirect::to($redirect);
            } else if ($profile_status == 0 && $user_status == 0) {
                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect);
            } else if ($user_status == 0) {
                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect);
            } else if ($user_status == 0) {

                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect)
                                ->withErrors(array('password' => 'Your are not approved by admin yet.'));
            }
        } else if ($user_type == 'admin' || $user_type == 'user') {

            if ($user_status == 1) {
                $redirect = 'admin';
                return Redirect::to($redirect);
            } else {
                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect)
                                ->withErrors(array('password' => 'Your are not approved by admin yet.'));
            }
        } else if ($user_type == 'customer') {

            if ($profile_status < 1 && $user_status > 0) {

                $redirect = 'customer-profile-complete';
                return Redirect::to($redirect);
                ;
            } else if ($profile_status > 0 && $user_status > 0) {

                $redirect = $user_type;
                return Redirect::to($redirect);
                ;
            } else if ($profile_status < 1 && $user_status < 1) {

                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect);
                ;
            } else if ($user_status == 0) {

                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect)
                                ->withErrors(array('password' => 'Your are not approved by admin yet.'));
            } else if ($user_status == 0) {
                $redirect = '/';
                Auth::logout();
                return Redirect::to($redirect);
            }
        }
    }

}

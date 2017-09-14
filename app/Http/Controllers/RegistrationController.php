<?php

namespace App\Http\Controllers;

use App\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    // protected $layout = 'layouts.onecolumn';
    public function index()
    {

        
        $customer=DB::table('user_type')->where('title', 'customer')->pluck('id');//storing id of customer id
        $vendor=DB::table('user_type')->where('title', 'vendor')->pluck('id'); //storing id of vendor id
        
        return view('pages.customer.registration')->with('customer', $customer)->with('vendor', $vendor);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        
          $rules = [
            'first_name'            => 'required|min:2|max:80|alpha',
            'last_name'             => 'required|min:2|max:80|alpha',
            'email'                 => 'required|email|between:3,64|unique:user',
            'username'              => 'required|unique:user',
            'password'              => 'required|between:4,20|confirmed',
            'password_confirmation' => 'same:password',
            'type_id'               => 'required'
          ];
          $validator = Validator::make(Request::all(), $rules);


          if ($validator->fails()) {
              return redirect('user-register')
                            ->withErrors($validator)
                            ->withInput(Request::except('password'));
          } else {
                $user               = new Registration;
                $user->first_name   = Request::get('first_name');
                $user->last_name    = Request::get('last_name');
                $user->email        = Request::get('email');
                $user->company      = Request::get('company');
                $user->username     = Request::get('username');
                $user->type_id      = '1';
                $user->user_role_id = '0';
                $user->status       = '0';

                $user->password     = Hash::make(Request::get('password'));
                if ($user->save()) {
                    $id = $user->id;
             
                    $email_data = [
                    'first_name'    => Request::get('first_name'),
                    'last_name'     => Request::get('last_name'),
                    'username'      => Request::get('username'),
                    'email'         => Request::get('email'),
                    'id'            => $id,
                    
                    ];
                
                    Mail::send('emails.customer_registered', $email_data, function ($message) {
                 
                        $message->to(Request::get('email'), Request::get('first_name').' '.Request::get('last_name'))
                         ->subject('Welcome to the GSS!')
                         ->from('imran@invortex.com', 'GSS');
                    });
                    Session::flash('message', 'Your account has been created successfully.');
                    return redirect('user-register');
                }
              }
    }

    
   
    
    public function completeProfile($id)
    {
       
        $type_id = Registration::userTypeId($id);
        $type = Usertype::checkUserType($type_id);
        
  
        if ($type == 'Customer') {
        } elseif ($type == 'Vendor') {
        }
     
                       
                       return view('pages.profile_completation')
            ->with('user_detail', $user_detail);

        // show the view and pass the nerd to it
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

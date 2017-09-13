<?php

class RegistrationController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    // protected $layout = 'layouts.onecolumn';
    public function index() {

        
        $customer=DB::table('user_type')->where('title','customer')->pluck('id');//storing id of customer id
        $vendor=DB::table('user_type')->where('title','vendor')->pluck('id'); //storing id of vendor id
        
        return View::make('pages.customer.registration')->with('customer', $customer)->with('vendor',$vendor);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        
          $rules = array(
            'first_name'            => 'required|min:2|max:80|alpha',
            'last_name'             => 'required|min:2|max:80|alpha',
            'email'                 => 'required|email|between:3,64|unique:user',
            'username'              => 'required|unique:user',
            'password'              => 'required|between:4,20|confirmed',
            'password_confirmation' => 'same:password',
            'type_id'               => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {
            return Redirect::to('user-register')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {

            $user               = new Registration;
            $user->first_name   = Input::get('first_name');
            $user->last_name    = Input::get('last_name');
            $user->email        = Input::get('email');
            $user->company      = Input::get('company');
            $user->username     = Input::get('username');
            $user->type_id      = '1';
            $user->user_role_id = '0';
            $user->status       = '0';

            $user->password     = Hash::make(Input::get('password')); 
            if($user->save()){
             $id = $user->id;
             
            $email_data = array(
                'first_name'    => Input::get('first_name'),
                'last_name'     => Input::get('last_name'),
                'username'      => Input::get('username'),
                'email'         => Input::get('email'),
                'id'            => $id,
                    
            );    
                
             Mail::send('emails.customer_registered', $email_data, function($message){
                 
             $message->to(Input::get('email'), Input::get('first_name').' '.Input::get('last_name'))
                     ->subject('Welcome to the GSS!')
                     ->from('imran@invortex.com', 'GSS' );
             });  
             Session::flash('message', 'Your account has been created successfully.');
             return Redirect::to('user-register');  
            }
            
        }
    }

    
   
    
    public function completeProfile($id){
       
        $type_id = Registration::userTypeId($id);
        $type = Usertype::checkUserType($type_id);
        
  
        if($type == 'Customer'){
            
        } elseif ($type == 'Vendor') {
        
       }
     
                       
                       return View::make('pages.profile_completation')
			->with('user_detail', $user_detail);

		// show the view and pass the nerd to it
		
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}

<?php

class RegistrationConroller extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    
      // protected $layout = 'layouts.onecolumn';
	public function index()
	{
	 return View::make('pages.customer.registration');	
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
       
             $user = new User;
$user->first_name = 'ak';
$user->last_name = 'ak';
$user->email = 'ak@yahoo.com';

$user->save();

            
//		$rules = array(
//			'first_name'       => 'required|min:2|max:80|alpha',
//                        'last_name'     => 'required|min:2|max:80|alpha',
//			'email'      => 'required|email|between:3,64',
//                        'username' => 'required',
//                        'password' => 'required|between:4,20|confirmed',
//                        'password_confirmation' => 'same:password',
//                        'type_id' => 'required'
//			
//		);
//		$validator = Validator::make(Input::all(), $rules);
//
//		// process the login
//		if ($validator->fails()) {
//			return Redirect::to('user-register')
//				->withErrors($validator)
//				->withInput(Input::except('password'));
//		} else {
//                    
//                    
////                    $nerd = new Nerd;
////			$nerd->name       = Input::get('name');
////			$nerd->email      = Input::get('email');
////			$nerd->nerd_level = Input::get('nerd_level');
////			$nerd->save();
////
////			// redirect
////			Session::flash('message', 'Successfully created nerd!');
////			return Redirect::to('nerds');
//         
//          //  return Redirect::to('/')->with('message', 'Thanks for registering!');
//
//
//                        $user = new User;
//                        
//			$user->first_name       = Input::get('first_name');
//                   
//			$user->last_name      = Input::get('last_name');
//			$user->email      = Input::get('email');
//                        
//                      
//                        
//			$user->save();
//
//			// redirect
//			echo "herere after save";
//		
//		}
            
          
           
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

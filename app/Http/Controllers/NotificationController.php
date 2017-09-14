<?php

class NotificationController extends \BaseController
{

    public static function sendNotification($recepient_id = array(), $message = null, $notification_type_id = 0, $email_data = array())
    {

        $notification_types = DB::table('notification_types')->where('id', $notification_type_id)->first();

        $activity_type = $notification_types->activity_type;
        $email_template = $notification_types->email_template;

        $email_data['email_text'] = $message;

        $recepient_id_string = implode(',', $recepient_id);
        $data['sender_id'] = 1;
        $data['recepient_id'] = $recepient_id_string;
        $data['message'] = $message;
        $data['notification_type_id'] = $notification_type_id;
        $data['is_read'] = 1;

        $notification = Notification::add($data);
        $to_email =  User::getAllUsersEmailByIds($recepient_id);

        if (sizeof($to_email) > 0) {
            $subject = $activity_type;
            Email::send($to_email, $subject, $email_template, $email_data);
        }

        return $notification;
    }


    public static function doNotification($recepient_id = 1, $sender_id = 1, $message = null, $notification_type_id = 0, $email_data = array(), $notification_url = "")
    {

        $notification_types = DB::table('notification_types')->where('id', $notification_type_id)->first();

        $activity_type = $notification_types->activity_type;
        $email_template = $notification_types->email_template;

        $email_data['email_text'] = $message;

        $data['sender_id'] = $sender_id;
        $data['recepient_id'] = $recepient_id;
        $data['message'] = $message;
        $data['notification_type_id'] = $notification_type_id;
        $data['notification_url'] = $notification_url;
        $data['is_read'] = 1; //Not read yet

        $notification = Notification::add($data);
        if (!empty($email_data)) {
            $user = User::find($recepient_id);
            if (isset($user->email)) {
                return $user->email;
                $subject = $activity_type;
                Email::send($user->email, $subject, $email_template, $email_data);
            } else {
            }
        }
        
        return $notification;
    }

  
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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

    public function ChangeNotificationStatus()
    {
         $data = Input::all();
         Notification::where('id', '=', $data['notification_id'])
                        ->update(array('is_read'=>0));
    }
    public function ChangeAllNotificationStatus()
    {
       
               Notification::where('id', '>=', 1)
                        ->update(array('is_read'=>0));
         return Redirect::back();
    }
    
    public function listNotifications()
    {
        $getNotifications    =  Notification::getNotificationsAll(Auth::user()->id);

    
        return View::make('pages.notifications')// return to page
                        ->with('get_notifications', $getNotifications);
    }
}

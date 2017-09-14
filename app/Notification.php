<?php

class Notification extends BaseTenantModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $fillable = array('id', 'sender_id', 'recepient_id', 'message', 'notification_type_id', 'notification_url','is_read', 'created_date');
    public function sender()
    {
        return $this->belongsTo('User', 'sender_id');
    }
    public function recepient()
    {
        return $this->belongsTo('User', 'recepient_id');
    }
    public static function add($data)
    {
        $notification = self::create($data);
        return $notification;
    }

      //Gettting notifications for login uses
    public static function getNotifications($user_id = 1)
    {
        $notification =   self::where('recepient_id', '=', $user_id)
                                         ->orderBy('id', 'desc')
                                         ->where('is_read', '=', 1)
                                         ->skip(0)
                                         ->take(5)
                                         ->get();
        return  $notification;
    }

       //Gettting notifications for login uses
    public static function getNotificationsAll($user_id = 1)
    {
        $notification =   self::where('recepient_id', '=', $user_id)
                                         ->orderBy('id', 'desc')
                                         
                                         ->get();
        return  $notification;
    }
}

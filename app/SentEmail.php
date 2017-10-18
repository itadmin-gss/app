<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $table = 'sent_email';
    protected $fillable = ['first_name', 'email', 'subject', 'body', 'template'];

    public static function add($email_data, $subject, $template)
    {   
        $data = [
            'first_name' => $email_data['first_name'],
            'email' => $email_data['email'],
            'body' => $email_data['user_email_template'],
            'subject' => $subject,
            'template' => $template
        ];
        $save = self::create($data);
        return $save->id;
    }
}

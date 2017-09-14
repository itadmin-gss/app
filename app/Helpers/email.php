<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Email
{

    public static function send($to_email = [], $subject, $template, $email_data = [])
    {

        $from_email = Config::get('app.admin_email');
        $from_name = Config::get('app.from_email_name');
        $email_data['user_email_template'].='<div dir="ltr"><div class="adM">
</div><p class="MsoNormal" style="line-height:105%">
<a href="http://www.gssreo.com/" target="_blank"><span style="font-size:10pt;line-height:105%;font-family:&quot;Times&quot;,serif;text-decoration:none"><img src="http://www.gssreo.com/wp-content/uploads/2015/03/gsslogo.png" border="0" height="58" width="138" class="CToWUd"></span></a><span style="font-size:11pt;line-height:105%;font-family:&quot;Calibri&quot;,sans-serif"></span></p>
<p class="MsoNormal" style="line-height:105%">
<span style="font-size:9pt;line-height:105%;font-family:&quot;Arial&quot;,sans-serif;color:rgb(82,82,82)">Phone Number: <a href="tel:855.787.4672" value="+8557874672" target="_blank"> +855-787-4672</a> </p>
<p class="MsoNormal" style="line-height:105%">
<span style="font-size:9pt;line-height:105%;font-family:&quot;Arial&quot;,sans-serif;color:rgb(82,82,82)">Website:
<a href="http://www.gssreo.com/" target="_blank"><span style="font-size:9pt;line-height:105%;font-family:&quot;Arial&quot;,sans-serif;color:rgb(82,82,82)">www.gssreo.com</span></a><span style="font-size:9pt;line-height:105%;font-family:&quot;Arial&quot;,sans-serif;color:rgb(82,82,82)"> 
</span>
</u></p>

</div>';

 
        $userStatus= User::where('email', '=', $to_email)->pluck("status");

        if ($userStatus == 1) {
            Mail::send($template, $email_data, function ($message) use ($from_email, $to_email, $subject, $from_name) {
                    $message->to($to_email)
                    ->subject($subject)
                    ->from($from_email, $from_name);
            });
        }
    }
}

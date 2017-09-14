<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;


class General
{

    public static function displayDate($date)
    {
        $formated_date = date("Y-m-d", strtotime($date));
        return $formated_date;
    }

    public static function displayTime($hours, $minutes, $meridiem)
    {

        $time = $hours . ':' . $minutes . ' ' . $meridiem;
        return $time;
    }

    public static function randomNumber($min, $max)
    {
        $number = rand($min, $max);
        return $number;
    }

    public static function validationErrors($validator)
    {
        $validation_messages = $validator->messages()->all();
        $all_messages = '';
        foreach ($validation_messages as $validation_message) {
            $all_messages .= FlashMessage::DisplayAlert($validation_message, 'error');
        }

        return $all_messages;
    }

    public static function writeMessage($message)
    {
        $message = FlashMessage::messages($message); //Generate messsage from FlashMessage
        Session::flash('message', FlashMessage::DisplayAlert($message, 'success'));
    }

    public static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = [];
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}

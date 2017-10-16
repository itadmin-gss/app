<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Request;

class PruvanController extends Controller
{
    /**
     * Validate End-Point
     * 
     * Validates application when the "Validate" button is clicked
     * On the Create/Edit Inegrations area of Pruvan. Saves Push Key
     * 
     */
    public function validateApp()
    {
        $request = Request::all();
        if ($request['username'] !== env('PRUVAN_USER') || $request['token'] !== env('PRUVAN_TOKEN'))
        {
            return json_encode(["error" => "invalid username or password", "validated" => false]);
        }
        else
        {
            return json_encode(["error" => "", "validated" => true]);
        }
    }

    //Get Work Orders End-point
    public function getWorkOrders()
    {
        return false;
    }

    //Upload Photos End-point
    public function uploadPictures()
    {
        return false;
    }

    //Status End-point
    public function status()
    {
        return false;
    }
}

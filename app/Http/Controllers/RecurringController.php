<?php

/**
 *
 * Recuring Controller.
 * Its Handles the Recuring Request.
 * @copyright Copyright 2014 Invortex Technology Development Team
 * @version $Id: 1.0
 */
class RecurringController extends Controller
{

    public static function showrecurring()
    {

        $recurrings = Recurring::all();
    
        return view('pages.admin.recurring')->with('recurrings', $recurrings);
    }

    public static function showVendorrecurring()
    {

        $recurrings = Recurring::where('vendor_id', '=', Auth::user()->id)->get();
    
        return view('pages.vendors.recurring')->with('recurrings', $recurrings);
    }
    public static function showCustomerrecurring()
    {

        $recurrings = Recurring::get();
    
        return view('pages.customer.recurring')->with('recurrings', $recurrings);
    }
    public function updateAdminRecurring($recurring_id)
    {
        $recurrings = Recurring::find($recurring_id);

        $vendors=User::where('type_id', '=', 3)->get();
        return view('pages.admin.edit_recurring')
        ->with('recurrings', $recurrings)
        ->with('vendors', $vendors);
    }
    public function updatevendorid()
    {
        $recurring_id=Input::get('recurring_id');
        $vendorid=Input::get('vendorid');

         $vname=Recurring::where("id", $recurring_id)
        ->update(["vendor_id"=>$vendorid]);


         echo "Vender name has been updated for the recurring request.";
    }

    public function deleteAdminRecurring($recurring_id)
    {
        Recurring::where('id', '=', $recurring_id)->delete();
        return Redirect::back()
                 ->with('message', FlashMessage::displayAlert("Request has been deleted", 'success'));
    }
}

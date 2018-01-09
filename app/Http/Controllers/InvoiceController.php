<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class InvoiceController extends Controller
{



    public function editInvoice($order_id)
    {

        //Show dashboard of customer
        $submitted = Request::get('submitted');
        if ($submitted) {
        } else {
            $order = Invoice::where('id', '=', $order_id)->get();
            $order_details =[];
            
            return view('common.edit_invoice')
            ->with('order', $order[0])
            ->with('order_details', $order_details);

           // return view('common.edit_invoice')->with('order', $order)->with('order_details', $order_details)->with('before_image',$before_image);
        }
    }
    public function listAdminInvoices($userTypeId = 2)
    {


        
        $invoices = Invoice::listAll($userTypeId);


        $list_orders = [];
        $i = 0;


        foreach ($invoices as $invoice) {
            $order_details = ($invoice->order->orderDetail);
             $list_orders[$i]['id'] = $invoice->id;
            $list_orders[$i]['order_id'] = $invoice->order_id;
            $list_orders[$i]['customer_name'] = $invoice->order->customer->first_name . ' ' . $invoice->order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $invoice->order->vendor->first_name . ' ' . $invoice->order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $invoice->order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['propery_address'] = $invoice->order->maintenanceRequest->asset->property_address;
            $list_orders[$i]['zip'] = $invoice->order->maintenanceRequest->asset->zip;
              $list_orders[$i]['ClientType'] = $invoice->order->maintenanceRequest->asset->customerType->title;
            $list_orders[$i]['city'] = $invoice->order->maintenanceRequest->asset->city->name;
            $list_orders[$i]['state'] = $invoice->order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['completion_date'] = date('m/d/Y h:i:s A', strtotime($invoice->order->completion_date));
           
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($invoice->order->created_at));
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $invoice->status;
            $list_orders[$i]['price'] = $invoice->total_amount;
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }


        return view('pages.admin.list_invoices')
                        ->with('orders', $list_orders)
                        ->with('status', $userTypeId);
    }

    public function listCustomerInvoices()
    {

        $userTypeId=2;
        
        $invoices = Invoice::listAll($userTypeId, Auth::user()->id);


        $list_orders = [];
        $i = 0;


        foreach ($invoices as $invoice) {
            $order_details = ($invoice->order->orderDetail);
            $list_orders[$i]['order_id'] = $invoice->order_id;
            $list_orders[$i]['customer_name'] = $invoice->order->customer->first_name . ' ' . $invoice->order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $invoice->order->vendor->first_name . ' ' . $invoice->order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $invoice->order->maintenanceRequest->asset->asset_number;
            // $list_orders[$i]['propery_address'] = $invoice->order->maintenanceRequest->asset->address;
            $list_orders[$i]['propery_address'] = $invoice->order->maintenanceRequest->asset->property_address;
            $list_orders[$i]['ClientType'] = $invoice->order->maintenanceRequest->asset->customerType->title;
            $list_orders[$i]['zip'] = $invoice->order->maintenanceRequest->asset->zip;

            $list_orders[$i]['city'] = $invoice->order->maintenanceRequest->asset->city->name;
            $list_orders[$i]['state'] = $invoice->order->maintenanceRequest->asset->state->name;

            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($invoice->order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $invoice->status;
            $list_orders[$i]['price'] = $invoice->total_amount;
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }


        return view('pages.customer.list_invoices')
                        ->with('orders', $list_orders)
                        ->with('status', $userTypeId);
    }

    public function listVendorInvoices()
    {

        $userTypeId=3;
        
        $invoices = Invoice::listAll($userTypeId, Auth::user()->id);


        $list_orders = [];
        $i = 0;


        foreach ($invoices as $invoice) {
            $order_details = ($invoice->order->orderDetail);
 
            $list_orders[$i]['order_id'] = $invoice->order_id;
            $list_orders[$i]['customer_name'] = $invoice->order->customer->first_name . ' ' . $invoice->order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $invoice->order->vendor->first_name . ' ' . $invoice->order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $invoice->order->maintenanceRequest->asset->asset_number;
             $list_orders[$i]['propery_address'] = $invoice->order->maintenanceRequest->asset->address;
            $list_orders[$i]['zip'] = $invoice->order->maintenanceRequest->asset->zip;

            $list_orders[$i]['city'] = $invoice->order->maintenanceRequest->asset->city->name;
            $list_orders[$i]['state'] = $invoice->order->maintenanceRequest->asset->state->name;

            $list_orders[$i]['order_date'] =date('m/d/Y h:i:s A', strtotime($invoice->order->created_at));
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $invoice->status;
            $list_orders[$i]['price'] = $invoice->total_amount;
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }


        return view('pages.vendors.list_invoices')
                        ->with('orders', $list_orders)
                        ->with('status', $userTypeId);
    }

    public function changePrice()
    {
        $invoice_id= Request::get('invoice_id');
        $invoice_price= Request::get('invoice_price');
     
        $flag= Invoice::where('id', '=', $invoice_id)->update(['total_amount' =>$invoice_price]); //Assigned to Technician Status
        echo "You has updated the price for vendor";
    }
}
